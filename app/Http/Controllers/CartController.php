<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *//* 
    public function index()
    {
        //
    } */

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            if (!empty($request)) {
                $data = Cart::create([
                    "user_id" => $request->user_id,
                    "product_id" => $request->product_id,
                    "quantity" => $request->quantity,
                ]);
                return response()->json(["message" => "Product added your cart"], Response::HTTP_CREATED);
            } else {
                return response()->json(["message" => "Cant add product"], Response::HTTP_BAD_REQUEST);
            }
        } catch (Exception $e) {
            return response()->json(["message" => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        try {
            $data = Cart::query()
                ->join("users", "cart.user_id", "=", "users.id")
                ->join("products", "products.id", "=", "cart.product_id")
                ->join("product_images", "product_images.product_id", "=", "products.id")
                ->select("users.id", "products.id", "products.name", "product_images.image", "cart.quantity")
                ->where("users.id", $user->id)
                ->get(); 
            return response()->json($data);
        } catch (Exception $e) {
            return response()->json(["message" => $e->getMessage()], Response::HTTP_NO_CONTENT);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cart $cart)
    {
        
        try {
            if ($request) {
                $data = Cart::where("id", $cart->id)
                ->where("user_id", $request->user_id)
                ->where("product_id", $request->product_id)
                ->update($request->only("quantity",$request->quantity));
                return response()->json(["message"=>"Update cart sucess"], Response::HTTP_OK);    
            }else {
                return response()->json(["message"=>"Update faled"], Response::HTTP_NOT_ACCEPTABLE);    
            }
        }catch (Exception $e) {
            return response()->json(["message"=> $e->getMessage()], Response::HTTP_NO_CONTENT);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        try {
            $data  = Cart::find($cart->id)->delete();
        } catch (Exception $e ) {
            return response()->json(["message"=> $e->getMessage()], Response::HTTP_NOT_ACCEPTABLE);
        }
    }
}
