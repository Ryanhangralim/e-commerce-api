<?php

namespace App\Http\Controllers\V1;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();

        return response()->json($categories, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['Required', 'min:3', 'max:25', 'unique:categories']
        ]);

        $validatedData['name'] = ucwords(strtolower($validatedData['name']));

        // Insert data
        Category::create($validatedData);

        return response()->json(['message' => 'Category Stored'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(category $category)
    {
        return response()->json($category, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, category $category)
    {
        $validatedData = $request->validate([
            'name' => ['Required', 'min:3', 'max:25', 'unique:categories']
        ]);

        $validatedData['name'] = ucwords(strtolower($validatedData['name']));

        // Update Category
        Category::findOrFail($request["categoryID"])->update(['name' => $validatedData['name']]);

        return response()->json(['message' => 'Category Updated'], 201);
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, category $category)
    {
        Category::destroy($request["categoryID"]);
        
        return response()->json(['message' => 'Category Deleted'], 201);
    }
}
