<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = ProductCategory::with('children')->whereNull('parent_id')->get();

        return response()->json([
            'categories' => $categories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $check_authentication = Auth::user();
        if ($check_authentication && $check_authentication->hasRole('admin')) {
            $validatedData = $this->validate($request, [
                'name' => 'required|min:3|max:255|string',
                'parent_id' => 'sometimes|nullable|numeric'
            ]);

            $category = ProductCategory::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Record created successfully!',
                'category' => $category,
            ]);
        } else {
            return response()->json([
                'message' => $check_authentication,
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = ProductCategory::find($id);
        return response()->json([
            'category' => $category,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $check_authentication = Auth::user();
        if ($check_authentication && $check_authentication->hasRole('admin')) {
            $validatedData = $this->validate($request, [
                'name' => 'required|min:3|max:255|string'
            ]);
            $category = ProductCategory::find($id);

            if ($category->update($validatedData)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Record updated successfully.',
                    'category' => $category,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No record updated.',
                    'category' => $category,
                ]);
            }
        } else {
            return response()->json([
                'message' => $check_authentication,
            ], 200);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $check_authentication = Auth::user();
        if ($check_authentication && $check_authentication->hasRole('admin')) {
            $category = ProductCategory::find($id);
            $product = 0;
            if($category){
            if ($category->children) {
                foreach ($category->children()->with('products')->get() as $child) {
                    foreach ($child->products as $product) {
                        $product->update(['product_category_id' => NULL]);
                    }
                }

                $category->children()->delete();
            }

            foreach ($category->products as $product) {
                $product->update(['product_category_id' => NULL]);
            }



            $category->delete();

            return response()->json(['message' => 'Category deleted', 'Category id deleted from products' => $product], Response::HTTP_OK);
            /*if (!$product) {
                return response()->json(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
            }*/
        }else{
            return response()->json([
                'message' => 'No record found!',
            ], 200);
        }
        } else {
            return response()->json([
                'message' => $check_authentication,
            ], 200);
        }
    }
}
