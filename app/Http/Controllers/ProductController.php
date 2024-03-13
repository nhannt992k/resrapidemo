<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Exception;
use Illuminate\Http\Response;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $products = Product::query()
                ->join("product_images", "products.id", "=", "product_images.product_id")
                ->select("products.id", "products.name", "products.price", "product_images.image")
                ->get();
            return response()->json($products);
        } catch (Exception $e) {
            return response()->json(["message" => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
   /*  public function store(Request $request)
    {
        try {
            if (!empty($request)) {
                $paths = [];
                $files = [];
                if ($request->hasFile('images')) {
                    foreach ($request->file('images') as $file) {
                        $name = $file->getClientOriginalName();
                        $path = $file->storeAs('uploads', $name, 'public');
                        $paths[] = Storage::url($path);
                        $files[] = ProductImage::create([
                            'image' => end($paths)
                        ]);
                    };
                }
                $data = Product::create([
                    "name" => $request->name,
                    "description" => $request->description,
                    "price" => $request->price,
                    "seller_id" => $request->seller_id,
                    "category_id" => $request->category_id,

                ]);
                foreach ($files as $file) {
                    $file->product_id = $data->id;
                    $file->save();
                }

                return response()->json($data);
            }
        } catch (Exception $e) {
            return response()->json(["message" => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
 */
public function store(Request $request)
{   
    $credentials = $request->validate([
        "name"=> "required|max:50",
        "description" => "required|max:255",
        "price"=> "required",
        "category_id" => "required|exists:categories,id",
    ]);
    try {
        if ($credentials) {
            $data = Product::create([
                "name" => $request->name,
                "description" => $request->description,
                "price" => $request->price,
                "seller_id" => $request->seller_id,
                "category_id" => $request->category_id,

            ]);
            return response()->json($data);
        }
    } catch (Exception $e) {
        return response()->json(["message" => $e->getMessage()], Response::HTTP_BAD_REQUEST);
    }
}


    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        try {
            $data  = Product::where("products.id",$product->id)
            ->join("product_images", "products.id", "=", "product_images.product_id")
            ->select("products.id", "products.name", "products.price","products.description", "product_images.image")->get();
            return response()->json($data);
        } catch (Exception $e) {
            return response()->json(["message" => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $credentials = $request->validate([
            "name"=> "required|max:50",
            "description" => "required|max:255",
            "price"=> "required",
            "category_id" => "required|exists:categories,id",
        ]);
        try {
            if ($credentials) {
                $fields = $request->only("name","desription","price","category_id");
                $fields = array_filter($fields, fn($value) => !is_null($value));
                if (!empty($fields)) {
                    $data = Product::findOrFail($product->id)->update($fields);
                    return response()->json(["message" => "Update product success"],Response::HTTP_OK);
                }else{
                    return response()->json(["message"=> "Update empty"], Response::HTTP_NO_CONTENT);
                }

            }else {
                return response()->json(["message"=> "Has problem your request"], Response::HTTP_NOT_ACCEPTABLE);
                
            }
        } catch (Exception $e) {
            return response()->json(["message"=> $e->getMessage()], Response::HTTP_NOT_ACCEPTABLE);
            
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            $data = Product::findOrFail($product->id)->delete();
            return response()->json(["message"=> "Delete product success"], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(["message"=> $e->getMessage()], Response::HTTP_OK);
            
        }
    }
}
