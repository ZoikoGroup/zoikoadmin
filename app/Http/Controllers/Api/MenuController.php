<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menus = Menu::query()
            ->where('status', 1)
            ->get();
        return response()->json([
            'menus' => $menus,
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
                $result = Menu::create($request->all());
                return response()->json([
                    'success' => true,
                    'message' => "Record created successfully.",
                    'menu' => $result,
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
        $menu = Menu::find($id);
        return response()->json([
            'menu' => $menu,
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
            $menu = Menu::find($id);

            if ($menu) {
                try {
                    if ($menu->update($request->all())) {
                        return response()->json([
                            "success" => true,
                            'message' => 'Record updated.',
                            $menu,
                        ], 200);
                    } else {
                        return response()->json([
                            "success" => false,
                            'message' => 'No record updated!',
                            $menu,
                        ], 200);
                    }
                } catch (\Illuminate\Database\QueryException $e) {
                    var_dump($e->errorInfo);
                }
            } else {
                return response()->json([
                    "success" => false,
                    'message' => 'No record found!.',
                    $menu,
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
            $menu = Menu::find($id);

            if (!$menu) {
                return response()->json(['message' => 'Record not found'], Response::HTTP_NOT_FOUND);
            }

            $menu->delete();
            return response()->json(['message' => 'Record deleted'], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => $check_authentication,
            ], 200);
        }
    }
}
