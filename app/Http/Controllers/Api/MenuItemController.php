<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class MenuItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(int $menu_id)
    {
        //$states = State::all();
        $menu_items = MenuItem::query()
            ->where("menu_id", $menu_id)
            ->orderByDesc('item_order')
            ->get();
        return response()->json([
            'states' => $menu_items,
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
                $result = MenuItem::create($request->all());
                return response()->json([
                    'success' => true,
                    'message' => "Record created successfully.",
                    'menu item' => $result,
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
        $menu_item = MenuItem::find($id);
        return response()->json([
            'menu item' => $menu_item,
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
                'url' => 'required|string|max:255',
            ]);
            $menu_item = MenuItem::find($id);

            if ($menu_item) {
                try {
                    if ($menu_item->update($request->all())) {
                        return response()->json([
                            "success" => true,
                            'message' => 'Record updated.',
                            $menu_item,
                        ], 200);
                    } else {
                        return response()->json([
                            "success" => false,
                            'message' => 'No record updated!',
                            $menu_item,
                        ], 200);
                    }
                } catch (\Illuminate\Database\QueryException $e) {
                    var_dump($e->errorInfo);
                }
            } else {
                return response()->json([
                    "success" => false,
                    'message' => 'No record found!.',
                    $menu_item,
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
            $menu_item = MenuItem::find($id);

            if (!$menu_item) {
                return response()->json(['message' => 'Record not found'], Response::HTTP_NOT_FOUND);
            }

            $menu_item->delete();
            return response()->json(['message' => 'Record deleted'], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => $check_authentication,
            ], 200);
        }
    }
}
