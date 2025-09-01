<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $faqs = Faq::query()
            ->orderByDesc('order')
            ->get();

        return response()->json([
            'faqs' => $faqs,
        ], 200);
    }

    public function faqsByPage($pageId)
{
    $faqs = Faq::with(['page'])
               ->where('page_id', $pageId)
               ->get();

    return response()->json($faqs);
    }

    public function faqsByProduct($productId)
    {
        $faqs = Faq::with(['product'])
                ->where('product_id', $productId)
                ->get();

        return response()->json($faqs);
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
                'question' => 'required|min:3|max:255|string'
            ]);
            if ($validatedData) {
                $result = Faq::create($request->all());
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
        $faq = Faq::find($id);
        return response()->json([
            'faq' => $faq,
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
                'question' => 'required|string|max:255',
            ]);
            $Faq = Faq::find($id);

            if ($Faq) {
                try {
                    if ($Faq->update($request->all())) {
                        return response()->json([
                            "success" => true,
                            'message' => 'Record updated.',
                            $Faq,
                        ], 200);
                    } else {
                        return response()->json([
                            "success" => false,
                            'message' => 'No record updated!',
                            $Faq,
                        ], 200);
                    }
                } catch (\Illuminate\Database\QueryException $e) {
                    var_dump($e->errorInfo);
                }
            } else {
                return response()->json([
                    "success" => false,
                    'message' => 'No record found!.',
                    $Faq,
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
            $faq = Faq::find($id);

            if (!$faq) {
                return response()->json(['message' => 'Record not found'], Response::HTTP_NOT_FOUND);
            }

            $faq->delete();
            return response()->json(['message' => 'Record deleted'], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => $check_authentication,
            ], 200);
        }
    }


}
