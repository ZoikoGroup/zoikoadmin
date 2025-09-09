<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(int $state_id)
    {
        $cities = City::where("state_id", $state_id)->get(["name", "id"]);
        return response()->json([
            'cities' => $cities,
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
    public function store(Request $request)
    {
        $check_authentication = Auth::user();
        if ($check_authentication && $check_authentication->hasRole('admin')) {
            $validatedData = $this->validate($request, [
                'name' => 'required|min:3|max:255|string',
                'state_id' => 'required|int',
            ]);

            if ($validatedData) {
                try {
                    $city = City::create($request->all());

                    return response()->json([
                        'success' => true,
                        'message' => 'Record created successfully!',
                        'city' => $city,
                    ]);
                } catch (\Illuminate\Database\QueryException $e) {
                    var_dump($e->errorInfo);
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
        $city = City::find($id);
        return response()->json([
            'city' => $city,
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
            $city = City::find($id);
            if ($validatedData) {
                if ($city->update($request->all())) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Record updated successfully.',
                        'city' => $city,
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'No record updated.',
                        'city' => $city,
                    ]);
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
            $discount_type = City::find($id);

            if (!$discount_type) {
                return response()->json(['message' => 'Record not found'], Response::HTTP_NOT_FOUND);
            }

            $discount_type->delete();
            return response()->json(['message' => 'Record deleted'], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => $check_authentication,
            ], 200);
        }
    }
}
