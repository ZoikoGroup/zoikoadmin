<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UserAddressController extends Controller
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
            if ($check_authentication->hasRole('admin') || $check_authentication->id == $request->user_id) {
                $validatedData = $this->validate($request, [
                    'type' => 'required|min:3|max:255|string'
                ]);
                if ($validatedData) {
                    $result = UserAddress::create($request->all());
                    return response()->json([
                        'success' => true,
                        'message' => "Record created successfully.",
                        'menu item' => $result,
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
    public function show(Request $request, int $id)
    {
        $check_authentication = Auth::user();
        if ($check_authentication) {
            if ($check_authentication->hasRole('admin') || $check_authentication->id == $id) {
                if (empty($request->type)) {
                    $user_address = UserAddress::query()
                        ->where("user_id", $id)
                        ->get();
                } else {
                    $user_address = UserAddress::query()
                        ->where("user_id", $id)
                        ->where('type', 'shipping')
                        ->get();
                }

                return response()->json([
                    'Address' => $user_address,
                ]);
            }
        } else {
            return response()->json([
                'message' => $check_authentication,
            ], 200);
        }
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
        if ($check_authentication) {
            if ($check_authentication->hasRole('admin') || $check_authentication->id == $request->user_id) {
                $validatedData = $request->validate([
                    'type' => 'required|string|max:255',
                ]);
                $user_address = UserAddress::find($id);

                if ($user_address) {
                    try {
                        if ($user_address->update($request->all())) {
                            return response()->json([
                                "success" => true,
                                'message' => 'Record updated.',
                                $user_address,
                            ], 200);
                        } else {
                            return response()->json([
                                "success" => false,
                                'message' => 'No record updated!',
                                $user_address,
                            ], 200);
                        }
                    } catch (\Illuminate\Database\QueryException $e) {
                        var_dump($e->errorInfo);
                    }
                } else {
                    return response()->json([
                        "success" => false,
                        'message' => 'No record found!.',
                        $user_address,
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $check_authentication = Auth::user();
        if ($check_authentication && $check_authentication->hasRole('admin')) {
            $user_address = UserAddress::find($id);

            if (!$user_address) {
                return response()->json(['message' => 'Record not found'], Response::HTTP_NOT_FOUND);
            }

            $user_address->delete();
            return response()->json(['message' => 'Record deleted'], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => $check_authentication,
            ], 200);
        }
    }
}
