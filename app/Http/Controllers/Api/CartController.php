<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CartController extends Controller
{
    /**
     * Display a listing of all carts (admin-only in future).
     */
    public function index()
    {
        $carts = Cart::all();
        return response()->json([
            'success' => true,
            'data' => $carts,
        ], Response::HTTP_OK);
    }

    /**
     * Show the form for creating a new resource (not used in API).
     */
    public function create()
    {
        return response()->json([
            'message' => 'This endpoint is not applicable for API.',
        ], Response::HTTP_NOT_IMPLEMENTED);
    }

    /**
     * Create a new cart for the user (if it doesn't already exist).
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
        ]);

        // Prevent duplicate cart creation
        $existingCart = Cart::where('user_id', $validatedData['user_id'])->first();

        if ($existingCart) {
            return response()->json([
                'success' => false,
                'message' => 'Cart already exists for this user.',
                'cart' => $existingCart,
            ], Response::HTTP_CONFLICT);
        }

        $cart = Cart::create([
            'user_id' => $validatedData['user_id'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cart created successfully.',
            'cart' => $cart,
        ], Response::HTTP_CREATED);
    }

    /**
     * Get cart by user ID.
     */
    public function show(string $user_id)
    {
        $cart = Cart::where('user_id', $user_id)->first();

        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Cart not found for this user.',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'cart' => $cart,
        ], Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource (not used in API).
     */
    public function edit(string $id)
    {
        return response()->json([
            'message' => 'This endpoint is not applicable for API.',
        ], Response::HTTP_NOT_IMPLEMENTED);
    }

    /**
     * Update an existing cart (for extension or admin use).
     */
    public function update(Request $request, string $id)
    {
        $cart = Cart::find($id);

        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Cart not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        // Example: Update cart fields here if needed (e.g. status)
        $cart->updated_at = now();
        $cart->save();

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully.',
            'cart' => $cart,
        ], Response::HTTP_OK);
    }

    /**
     * Delete a cart by its ID.
     */
    public function destroy(string $id)
    {
        $cart = Cart::find($id);

        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Cart not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        $cart->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cart deleted successfully.',
            'cart' => $cart,
        ], Response::HTTP_OK);
    }
}
