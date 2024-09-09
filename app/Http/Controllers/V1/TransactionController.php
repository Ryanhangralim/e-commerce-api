<?php

namespace App\Http\Controllers\V1;

use App\Models\Cart;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    // View transaction
    public function viewTransactions(Request $request)
    {
        // Get transaction type from query parameter, default to all
        $type = $request->query('type', 'all');

        // Build the base query for the transactions
        $query = Transaction::where('user_id', Auth::user()->id)
            ->orderBy('updated_at', 'desc');

        // Filter by the status type if provided and not 'all'
        if ($type !== 'all') {
            $query->where('status', $type);
        }

        // Get the filtered transactions
        $transactions = $query->get();
        $transaction_count = $this->getTransactionCount();
        $transaction_count['all'] = Transaction::where('user_id', Auth::user()->id)->count();

        // Pass the transactions and the current type to the view
        $data = [
            'transactions' => $transactions,
            'currentType' => $type,
            'transaction_count' => $transaction_count,
        ];

        return response()->json(['statusCode' => 200,
                                'message' => 'Data retrieved successfully',
                                'data' => ['transactions' => $data]], 200);
    }

    // Get transaction count for every type
    public function getTransactionCount()
    {
            // Define the statuses you want to count
            $statuses = ['pending', 'processing', 'delivered', 'received', 'completed', 'canceled'];

            // Initialize an associative array to store the counts
            $transactionCounts = [];

            // Loop through each status and get the count of transactions for the authenticated user
            foreach ($statuses as $status) {
                $transactionCounts[$status] = Transaction::where('user_id', Auth::user()->id)
                    ->where('status', $status)
                    ->count();
            }

            return $transactionCounts;
    }

    // Checkout function
    public function checkout(Request $request)
    {
        // Fetch products
        $selectedProductCartIds = $request['selected_products'];
        // Find product
        $selectedProductCarts = Cart::whereIn('id', $selectedProductCartIds)->get();

        // Check if stock is available for all products
        foreach( $selectedProductCarts as $productCart)
        {
            if($productCart->quantity > $productCart->product->stock)
            {
                return response()->json(['statusCode' => 200, 'message' => 'Stock unavailable!'], 200);
            }
        }
        
        // Create Transaction
        $transactionData['user_id'] = Auth()->user()->id;
        $transactionData['status'] = 'pending';
        $transactionData['business_id'] = $request['business_id'];
        $transaction = Transaction::create($transactionData);

        // Create order for each product
        foreach( $selectedProductCarts as $productCart)
        {
            OrderController::newOrder($transaction, $productCart);
        }

        return response()->json(['statusCode' => 201, 'message' => 'Transaction Successfully Checked Out!'], 201);
    }

    // Seller dashboard routes
    public function fetchTransactions(Request $request)
    {
        $status = $request->get('status');
        $business_id = Auth::user()->business->id;

        if($status === 'all'){
            $transactions = Transaction::with('user')->where('business_id', $business_id)->get();
        } else {
            $transactions = Transaction::with('user')->where(['status' => $status, 'business_id' => $business_id])->get();
        }
        return response()->json(['statusCode' => 200,
                                'message' => 'Data sucessfully retrieved',
                                'data' => ['transactions' => $transactions]], 200);
    }

    public function viewTransactionDetail(Transaction $transaction)
    {
        return response()->json(['statusCode' => 200, 
                                'message' => 'Data retrieved sucessfully', 
                                'data' => ['transactionData' => $transaction->makeHidden(['orders']), 'transactionOrders' => $transaction->orders]], 200);
    }

    // Update transaction status
    public function updateTransactionStatus(Request $request)
    {
        // get attributes
        $action = $request['action'];
        $transaction_id = $request['transaction_id'];

        // update transaction
        Transaction::where('id', $transaction_id)->update(['status' => $action]);

        // Redirect
        return response()->json(['statusCode' => 201, 'message' => 'Transaction status has been updated!'], 201);
    }
}
