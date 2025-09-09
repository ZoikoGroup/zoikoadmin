<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currencies = Currency::all();

        return response()->json([
            'currencies' => $currencies,
        ], 200);
    }

    /**
     * Display a listing of the resource using country id.
     */
    public function get_currency(int $country_id)
    {
        $currencies = Currency::where('country_id', '=', $country_id)->get();

        return response()->json([
            'currencies' => $currencies,
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
                $result = Currency::create($request->all());
                return response()->json([
                    'success' => true,
                    'message' => "Record created successfully.",
                    'currency' => $result,
                ], 200);
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
        $currency = Currency::find($id);
        return response()->json([
            'currency' => $currency,
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
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
            ]);
            $currency = Currency::find($id);

            if ($currency) {
                try {
                    if ($currency->update($request->all())) {
                        return response()->json([
                            "success" => true,
                            'message' => 'Record updated.',
                            $currency,
                        ], 200);
                    } else {
                        return response()->json([
                            "success" => false,
                            'message' => 'No record updated!',
                            $currency,
                        ], 200);
                    }
                } catch (\Illuminate\Database\QueryException $e) {
                    var_dump($e->errorInfo);
                }
            } else {
                return response()->json([
                    "success" => false,
                    'message' => 'No record found!.',
                    $currency,
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
     */
    public function destroy(string $id)
    {
        $check_authentication = Auth::user();
        if ($check_authentication && $check_authentication->hasRole('admin')) {
            $currency = Currency::find($id);

            if (!$currency) {
                return response()->json(['message' => 'Record not found'], Response::HTTP_NOT_FOUND);
            }

            $currency->delete();
            return response()->json(['message' => 'Record deleted'], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => $check_authentication,
            ], 200);
        }
    }
}
