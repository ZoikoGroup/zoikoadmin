<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlansController extends Controller
{
    public function plans()
    {
        $plans = Plan::all();
        return response()->json(['success' => true, 'data' => $plans]);
    }
    
    public function plan_type($type)
    {
        $plans = Plan::where('plan_type', $type)->get();
        return response()->json(['success' => true, 'data' => $plans]);
    }
    
    public function plan_by_id($id)
    {
        $plan = Plan::find($id);

        if (!$plan) {
            return response()->json(['success' => false, 'message' => 'Plan not found'], 404);
        }

        return response()->json(['success' => true, 'data' => $plan]);
    }

    public function create(Request $request)
{
    $validated = $request->validate([
        'price'         => 'required|numeric|min:0',
        'currency'      => 'required|string|max:10',
        'duration_type' => 'required|in:day,week,month,year',
        'features'      => 'nullable|array',
        'status'        => 'required|in:active,inactive,deleted',
        'order'         => 'nullable|integer',
        'plan_type'     => 'required|in:prepaid,postpaid',
    ]);

    $plan = Plan::create($validated);

    return response()->json([
        'success' => true,
        'message' => 'Plan created successfully',
        'data'    => $plan
    ], 201);
}

    public function update(Request $request, $id)
    {
        $plan = Plan::find($id);

        if (!$plan) {
            return response()->json(['success' => false, 'message' => 'Plan not found'], 404);
        }

        $validated = $request->validate([
            'price'         => 'sometimes|required|numeric|min:0',
            'currency'      => 'sometimes|required|string|max:10',
            'duration_type' => 'sometimes|required|in:day,week,month,year',
            'features'      => 'nullable|array',
            'status'        => 'sometimes|required|in:active,inactive,deleted',
            'order'         => 'nullable|integer',
            'plan_type'     => 'sometimes|required|in:prepaid,postpaid',
        ]);

        $plan->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Plan updated successfully',
            'data'    => $plan
        ]);
    }

    public function delete($id)
    {
        $plan = Plan::find($id);

        if (!$plan) {
            return response()->json(['success' => false, 'message' => 'Plan not found'], 404);
        }

        $plan->delete();

        return response()->json(['success' => true, 'message' => 'Plan deleted successfully']);
    }
}
