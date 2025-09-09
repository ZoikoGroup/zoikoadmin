<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
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
        //
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
        $user = Auth::user();
        if ($user) {
            if ($user->id == $request->id) {
                if ($user->name != $request->name) {
                    $rules['name'] = 'unique:users|required';
                }
                if ($user->email != $request->email) {
                    $rules['email'] = 'unique:users|required';
                }

                if (isset ($rules)) {
                    $input = $request->only('name', 'email', 'password');
                    $validator = Validator::make($input, $rules);
                    if ($validator->fails()) {
                        return response()->json(['success' => false, 'error' => $validator->messages()]);
                    }
                }

                $user->name = $request->name;
                $user->email = $request->email;
                if (!empty ($request->password)) {
                    $user->password = Hash::make($request->password);
                }
                $user->updated_at = date("Y-m-d H:i:s");

                if ($user->update()) {
                    return response()->json(['success' => true, 'message' => 'Record updated.', $user]);
                } else {
                    return response()->json(['success' => false, 'No record updated!']);
                }
            } else {
                return response()->json([
                    'error' => 'Only user can update own profile from this end point!',
                    'message' => $user,
                ], 200);
            }
        } else {
            return response()->json([
                'message' => $user,
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
