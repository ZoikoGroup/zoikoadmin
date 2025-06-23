<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\State;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(int $country_id)
    {
        //$states = State::all();
        $states = State::where("country_id", $country_id)->get(["name", "id"]);
        return response()->json([
            'states' => $states,
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
                'name' => 'required|min:3|max:255|string'
            ]);

            if ($validatedData) {
                try {
                    $state = State::create($request->all());

                    return response()->json([
                        'success' => true,
                        'message' => 'Record created successfully!',
                        'state' => $state,
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
        $state = State::find($id);
        return response()->json([
            'state' => $state,
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
            $state = State::find($id);
            if ($validatedData) {
                if ($state->update($request->all())) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Record updated successfully.',
                        'state' => $state,
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'No record updated.',
                        'state' => $state,
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
            $states = State::find($id);

            if (!$states) {
                return response()->json(['message' => 'Record not found'], Response::HTTP_NOT_FOUND);
            } else {
                $cities = City::where('state_id', '=', $id)->get();
                if ($cities) {
                    foreach ($cities as $city) {
                        $city->delete();
                    }
                }
            }

            $states->delete();
            return response()->json([
                'message' => 'Record deleted',
                'states' => $states,
                'cities' => $cities,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => $check_authentication,
            ], 200);
        }
    }
}
