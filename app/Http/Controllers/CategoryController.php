<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = Category::all();
            return response()->json($data);
        } catch (Exception $e) {
            return redirect()->back()->with(["message", $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $credentails = $request->validate([
            "name" => "required|max:50",
        ]);

        try {
            if ($credentails) {
                $data = Category::create(["name" => $request->value]);
                return response()->json(["message" => "Create category success"], Response::HTTP_CREATED);
            } else {
                return response()->json(["message" => "Create category fail"], Response::HTTP_NOT_ACCEPTABLE);
            }
        } catch (Exception $e) {
            return response()->json(["message" => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        try {
            if (!empty($category)) {
                $data = $category->where("categories.id", $category->id)
                    ->join('products', 'categories.id', '=', 'products.category_id')
                    ->join('product_images', 'product_images.product_id', '=', 'products.id')
                    ->select('categories.name', 'products.id', 'products.name', 'products.price', 'product_images.image')
                    ->get();

                return response()->json($data, Response::HTTP_OK);
            } else {
                return response()->json(['message' => ''], Response::HTTP_NOT_ACCEPTABLE);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_ACCEPTABLE);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $credentials = $request->validate([
            "name" => "required|max:50",
        ]);
        try {
            if ($credentials) {
                $data  = $category->find($category->id)
                    ->update($request->only("name", $request->name));
                return response()->json(["message" => "Update category success"], Response::HTTP_OK);
            } else {
                return response()->json(["message" => "Update category failed"], Response::HTTP_NOT_ACCEPTABLE);
            }
        } catch (Exception $e) {
            return response()->json(["message" => $e->getMessage()], Response::HTTP_NOT_ACCEPTABLE);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try {
            $category->find($category->id)->delete();
            return response()->json(["message" => "Delete category success"], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(["message" => $e->getMessage()], Response::HTTP_NOT_ACCEPTABLE);
        }
    }
}
