<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        if ($check_authentication) {
            $validatedData = $this->validate($request, [
                'order_id' => 'required|int',
                'product_id' => 'required|int',
                'quantity' => 'required|int',
            ]);
            if ($validatedData) {
                $existing_check = OrderItem::where('order_id', $request->order_id)->where('product_id', $request->product_id)->exists();
                if (!$existing_check) {
                    $result = OrderItem::create($request->all());
                    return response()->json([
                        'success' => true,
                        'message' => "Record created successfully.",
                        'order item' => $result,
                    ], 200);
                } else {
                    return response()->json([
                        'message' => 'Record already exist',
                    ], 200);
                }
            }
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
