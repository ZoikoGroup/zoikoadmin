<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response; // Import the Response class
use Illuminate\Http\Request;
use App\Models\Page;
use Illuminate\Support\Facades\Auth;


class PageController extends Controller
{

    /** For single page */
    public function index($id)
    {
        $pages = Page::find($id); // Retrieve all pages from the database
        return response()->json($pages, Response::HTTP_OK); // Return the data as JSON
    }
    /** End */

    //page
    public function store(Request $request)
    {
        $check_authentication = Auth::user();
        if ($check_authentication && $check_authentication->hasRole('admin')) {
            $validatedData = $request->validate([
                'title' => 'required|max:255',
                'content' => 'required',
            ]);

                // Check for duplicate title
            $existingPage = Page::where('title', $request->title)->first();

            if ($existingPage) {
                return response()->json([
                    'message' => 'Duplicate title already exists.'
                ], 409); // 409 Conflict
            }

            // Create a new page instance with the validated data
            $page = new Page([
                'title' => $validatedData['title'],
                'content' => $validatedData['content'],
                'og_title' => $request->og_title,
                'og_type' => $request->og_type,
                'og_description' => $request->og_description,
                'meta_title ' => $request->meta_title,
                'meta_keywords' => $request->meta_keywords,
                'meta_description' => $request->meta_description,
            ]);

            $page->save(); // Save the new page to the database

            return response()->json($page, Response::HTTP_CREATED); // Return the new page as JSON
        } else {
            return response()->json([
                'message' => $check_authentication,
            ], 200);
        }
    }

    //delete
    public function destroy($id)
    {
        $check_authentication = Auth::user();
        if ($check_authentication && $check_authentication->hasRole('admin')) {
            $page = Page::find($id);

            if (!$page) {
                return response()->json(['message' => 'Page not found'], Response::HTTP_NOT_FOUND);
            }

            $page->delete();
            return response()->json(['message' => 'Page deleted'], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => $check_authentication,
            ], 200);
        }
    }


    /** Get all pages */

    public function all_pages()
    {
        $check_authentication = Auth::user();
        if ($check_authentication && $check_authentication->hasRole('admin')) {
            $pages = Page::all(); // Retrieve all pages from the database
            return response()->json($pages, Response::HTTP_OK); // Return the data as JSON
        } else {
            return response()->json([
                'message' => $check_authentication,
            ], 200);
        }
    }

    /** End */


    /** Update page */

    public function update(Request $request)
    {
        $check_authentication = Auth::user();
        if ($check_authentication && $check_authentication->hasRole('admin')) {
            $id = $request->id;
            $page = Page::find($id);
            if ($page) {
                if ($page->update($request->all())) {
                    return response()->json([
                        "success" => true,
                        'message' => 'Record updated.',
                        $page,
                    ], 200);
                } else {
                    return response()->json([
                        "success" => false,
                        'message' => 'No record updated!',
                        $page,
                    ], 200);
                }
            }else{
                return response()->json([
                    "success" => false,
                    'message' => 'No record found!.',
                    $page,
                ], 200);
            }

        } else {
            return response()->json([
                'message' => $check_authentication,
            ], 200);
        }
    }
}
