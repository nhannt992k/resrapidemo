<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductImage;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

use function PHPUnit\Framework\isNull;

class ProductImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $data = ProductImage::query()
                ->join("products", "products.id", "=", "product_images.product_id")
                ->select("products.id", "products.name", "product_images.image")
                ->get()
                ->groupBy('id')
                ->map(function ($item) {
                    return [
                        'id' => $item[0]->id,
                        'name' => $item[0]->name,
                        'images' => $item->pluck('image')->toArray(),
                    ];
                })
                ->values();

            return response()->json($data);
        } catch (Exception $e) {
            return response()->json(["message" => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'image.*' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'image' =>  'required|array',
            'product_id' => 'required|exists:products,id',
        ]);
        // dd($request->all());
        try {
            if ($credentials) {
                if ($request->hasFile('image')) {
                    $paths = [];
                    $files = [];
                    foreach ($request->file('image') as $file) {
                        $name = $file->getClientOriginalName();
                        $path = $file->storeAs('uploads', $name, 'public');
                        $paths[] = Storage::url($path);
                        $files[] = ProductImage::create([
                            'product_id' => $request->product_id,
                            'image' => Storage::url($path)
                        ]);
                    };
                
                    return response()->json([
                        'message' => 'Images uploaded successfully',
                        'paths' =>  $paths,
                    ]);
                } else {
                    return response()->json(['message' => 'No images provided'], Response::HTTP_BAD_REQUEST);
                }
            }else {
                return response()->json(['message' => 'Wrong type'], Response::HTTP_BAD_REQUEST);

            }
           
        } catch (Exception $e) {
            return response()->json(["message" => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
        
    }

    /**
     * Display the specified resource.
     */
    /*    public function show(string $id)
    {
        //
    }
 */
    /**
     * Update the specified resource in storage.
     */

public function update(Request $request, ProductImage $productImage)
{
    try {
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $file) {
                $name = $file->getClientOriginalName();
                $path = $file->storeAs('uploads', $name, 'public');

                $productImage->update([
                    'product_id' => $request->product_id,
                    'image' => Storage::url($path)
                ]);
            }

            return response()->json([
                'message' => 'Images updated successfully',
            ]);
        } else {
            return response()->json(['message' => 'No images provided'], Response::HTTP_BAD_REQUEST);
        }
    } catch (Exception $e) {
        return response()->json(["message" => $e->getMessage()], Response::HTTP_BAD_REQUEST);
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductImage $productImage)
    {
        try {
            $data = $productImage->find($productImage->id)->delete();
            return response()->json(["message"=> "Delete image success"], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(["message"=> $e->getMessage()], Response::HTTP_NOT_ACCEPTABLE);
            
        }
    }
}
