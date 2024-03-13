<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /* public function index()
    {
    
    }
 */
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $credentials = $request->validate([
            "user_id" => "required",
        ]);
        try {
            $order_date = date('Y-m-d H:i:s', $request->get(now()->timestamp));
            if ($credentials) {
                $address = User::where("id", $request->user_id)
                ->select("address")->value("address");
                $data = Order::create([
                    "user_id"=> $request->user_id,
                    "order_date" =>$order_date,
                    "shipping_address" =>$address
                ]);
                return response()->json(["message" =>"Create order success"], Response::HTTP_CREATED);
            }else{
                return response()->json(["message" =>"Create order fail"], Response::HTTP_NOT_ACCEPTABLE);

            }
        } catch (Exception $e) {
            return response()->json(["message"=> $e->getMessage()], Response::HTTP_BAD_REQUEST);   
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        try {
            $data = Order::join("order_details", "order_details.order_id", "=", "orders.id")
                ->join("products", "products.id", "=", "order_details.product_id")
                ->join("product_images", "product_images.product_id", "=", "products.id")
                ->select("orders.id", "products.name", "product_images.image", "orders.order_date")
                ->where("user_id", $user->id)
                /* ->ddRawSql() */
                ->get();
            return response()->json($data);
        } catch (Exception $e) {
            return response()->json(["message" => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Update the specified resource in storage.
     */
/*     public function update(Request $request, string $id)
    {
        //
    }
 */
    /**
     * Remove the specified resource from storage.
     */
    /* public function destroy(string $id)
    {
        //
    } */
}
