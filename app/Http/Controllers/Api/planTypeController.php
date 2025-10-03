<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PlanType;
use Illuminate\Http\Request;

class PlanTypeController extends Controller
{
    public function plan_types()
    {
        $planTypes = PlanType::all();
        return response()->json(['success' => true, 'data' => $planTypes]);
    }

    
    public function plan_type_by_id($id)
    {
        $planTypes = PlanType::find($id);

        if (!$planTypes) {
            return response()->json(['success' => false, 'message' => 'Plan not found'], 404);
        }

        return response()->json(['success' => true, 'data' => $planTypes]);
    }

    public function create(Request $request)
{
    $validated = $request->validate([
        'name'         => 'required|string|min:3|max:255',
    ]);

    $PlanType = PlanType::create($validated);

    return response()->json([
        'success' => true,
        'message' => 'Plan created successfully',
        'data'    => $PlanType
    ], 201);
}

    public function update(Request $request, $id)
    {
        $PlanType = PlanType::find($id);

        if (!$PlanType) {
            return response()->json(['success' => false, 'message' => 'Plan not found'], 404);
        }

        $validated = $request->validate([
            'name'         => 'sometimes|required|numeric|min:0',
        ]);

        $PlanType->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Plan updated successfully',
            'data'    => $PlanType
        ]);
    }

    public function delete($id)
    {
        $PlanType = PlanType::find($id);

        if (!$PlanType) {
            return response()->json(['success' => false, 'message' => 'Plan not found'], 404);
        }

        $PlanType->delete();

        return response()->json(['success' => true, 'message' => 'Plan deleted successfully']);
    }
}
