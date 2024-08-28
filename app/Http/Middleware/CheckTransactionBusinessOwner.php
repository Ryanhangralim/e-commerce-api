<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTransactionBusinessOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Retrieve the transaction ID from the route
        $transaction = $request->route('transaction');

        if($transaction && $transaction->business->user_id !== auth()->user()->id) {
            return response()->json(['error' => 'You are not authorized to view this transaction'], 403);
        }

        return $next($request);
    }
}
