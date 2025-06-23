<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreProductAttributeRequest;
use App\Http\Controllers\Controller;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ProductAttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attributes = ProductAttribute::all();

        return response()->json([
            'attributes' => $attributes,
        ], 200);
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
    public function store(StoreProductAttributeRequest $request)
    {
        $check_authentication = Auth::user();
        if ($check_authentication && $check_authentication->hasRole('admin')) {
            /*$validatedData = $this->validate($request, [
                'name' => 'required|min:3|max:255|string',
                'product_id' => 'required',
            ]);*/

            if ($request->validated()) {
                $attribute = ProductAttribute::create($request->all());

                return response()->json([
                    'success' => true,
                    'message' => 'Record created successfully!',
                    'attribute' => $attribute,
                ]);
            } else {
                return response()->json([
                    'message' => $check_authentication,
                ], 200);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $attribute = ProductAttribute::find($id);
        return response()->json([
            'attribute' => $attribute,
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
    public function update(Request $request, string $id)
    {
        $check_authentication = Auth::user();
        if ($check_authentication && $check_authentication->hasRole('admin')) {
            $validatedData = $this->validate($request, [
                'name' => 'required|min:3|max:255|string'
            ]);
            $attribute = ProductAttribute::find($id);

            if ($attribute->update($request->all())) {
                return response()->json([
                    'success' => true,
                    'message' => 'Record updated successfully.',
                    'discount_type' => $attribute,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No record updated.',
                    'discount_type' => $attribute,
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
    public function destroy(string $id)
    {
        $check_authentication = Auth::user();
        if ($check_authentication && $check_authentication->hasRole('admin')) {
            $attribute = ProductAttribute::find($id);

            if (!$attribute) {
                return response()->json(['message' => 'Attribute not found!'], Response::HTTP_NOT_FOUND);
            }

            $attribute->delete();
            return response()->json(['message' => 'Attribute deleted.'], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => $check_authentication,
            ], 200);
        }
    }
}
