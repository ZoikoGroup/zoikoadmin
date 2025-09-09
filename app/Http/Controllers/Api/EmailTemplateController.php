<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class EmailTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $check_authentication = Auth::user();
        if ($check_authentication) {
            if ($check_authentication->hasRole('admin')) {
                $email_templates = EmailTemplate::all();
                return response()->json([
                    'email-templates' => $email_templates,
                ], 200);
            } else {
                $email_templates = EmailTemplate::find($check_authentication->id);
                return response()->json([
                    'email templates' => $email_templates,
                ], 200);
            }
        } else {
            return response()->json([
                'message' => $check_authentication,
            ], 200);
        }
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
                'template_name' => 'required|string',
            ]);
            if ($validatedData) {
                $existing_check = EmailTemplate::where('template_name', $request->template_name)->exists();
                if (!$existing_check) {
                    $result = EmailTemplate::create($request->all());
                    return response()->json([
                        'success' => true,
                        'message' => "Record created successfully.",
                        'order item' => $result,
                    ], 200);
                } else {
                    return response()->json([
                        'message' => 'Record already exist',
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
    public function show(int $id)
    {
        $email_template = EmailTemplate::find($id);
        return response()->json([
            'template' => $email_template,
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
            $order = EmailTemplate::find($id);
            if ($order) {
                if ($order->update($request->all())) {
                    return response()->json([
                        "success" => true,
                        'message' => 'Record updated.',
                        $order,
                    ], 200);
                } else {
                    return response()->json([
                        "success" => false,
                        'message' => 'No record updated!',
                        $order,
                    ], 200);
                }
            } else {
                return response()->json([
                    "success" => false,
                    'message' => 'No record found!.',
                    $order,
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
            $product = EmailTemplate::find($id);
            if (!$product) {
                return response()->json(['message' => 'Template not found'], Response::HTTP_NOT_FOUND);
            }
            $product->delete();
            return response()->json(['message' => 'Template deleted'], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => $check_authentication,
            ], 200);
        }
    }
}
