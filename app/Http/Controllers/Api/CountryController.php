<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $countries = Country::all();

        return response()->json([
            'countries' => $countries,
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
                    $country = Country::create($request->all());

                    return response()->json([
                        'success' => true,
                        'message' => 'Record created successfully!',
                        'country' => $country,
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
        $country = Country::find($id);
        return response()->json([
            'country' => $country,
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
            $country = Country::find($id);
            if ($validatedData) {
                if ($country->update($request->all())) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Record updated successfully.',
                        'country' => $country,
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'No record updated.',
                        'country' => $country,
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
            $country = Country::find($id);

            if (!$country) {
                return response()->json(['message' => 'Record not found'], Response::HTTP_NOT_FOUND);
            } else {
                $states = State::where('country_id', '=', $id)->get();
                if ($states) {
                    foreach ($states as $state) {
                        $cities = City::where('state_id', '=', $state->id)->get();
                        if ($cities) {
                            foreach ($cities as $city) {
                                $city->delete();
                            }
                        }
                        $state->delete();
                    }
                }
            }

            $country->delete();
            return response()->json([
                'message' => 'Record deleted',
                'countries' => $country,
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
