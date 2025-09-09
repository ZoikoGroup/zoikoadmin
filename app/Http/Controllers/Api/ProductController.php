<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreProductRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;



class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();

        return response()->json([
            'products' => compact('products'),
        ], 200);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::find($id);
        return response()->json([
            'product' => $product,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreProductRequest  $request
     * @return Illuminate\Http\JsonResponse
     */
    public function store(StoreProductRequest $request)
    {
        $check_authentication = Auth::user();
        if ($check_authentication && $check_authentication->hasRole('admin')) {
            if($request->validated()){
            $result = Product::create($request->all());

            return response()->json([
                'success' => true,
                'message' => "Product created successfully.",
                'products' => $result,
            ], 200);
        }
        } else {
            return response()->json([
                'message' => $check_authentication,
            ], 200);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateProductRequest  $request
     * @param  Product  $product
     * @return Illuminate\Http\JsonResponse
     */
    public function update(Request $request,  $id)
    {
        $check_authentication = Auth::user();
        if ($check_authentication && $check_authentication->hasRole('admin')) {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'price_uk' => 'required|numeric|min:0',
                'price_usa' => 'required|numeric|min:0',
            ]);
            $product = Product::find($id);

            if ($product) {
                if ($product->update($request->all())) {
                    return response()->json([
                        "success" => true,
                        'message' => 'Record updated.',
                        $product,
                    ], 200);
                } else {
                    return response()->json([
                        "success" => false,
                        'message' => 'No record updated!',
                        $product,
                    ], 200);
                }
            }else{
                return response()->json([
                    "success" => false,
                    'message' => 'No record found!.',
                    $product,
                ], 200);
            }

        } else {
            return response()->json([
                'message' => $check_authentication,
            ], 200);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  Product  $product
     * @return Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $check_authentication = Auth::user();
        if ($check_authentication && $check_authentication->hasRole('admin')) {
            $product = Product::find($id);

            if (!$product) {
                return response()->json(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
            }

            $product->delete();
            return response()->json(['message' => 'Product deleted'], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => $check_authentication,
            ], 200);
        }
    }

}
