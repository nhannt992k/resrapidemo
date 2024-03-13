<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   /*  public function index()
    {
        //
    }
 */
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {   
        try {
            $data = OrderDetail::where("order_id", $order->id)
                ->join("products","products.id","=","order_details.product_id")
                ->join("product_images","product_images.product_id","=","products.id")
                ->join("orders","order_details.order_id","=","orders.id")
                ->selectRaw("order_details.id,products.name, products.price, order_details.quantity,SUM(order_details.quantity * products.price) as total, orders.shipping_address, orders.order_date")
      /*           ->ddRawSql() */
                ->get();
                return response()->json($data);
            ;
        } catch (Exception $e) {
            return response()->json(["message" => $e->getMessage()],Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Update the specified resource in storage.
     */
   /*  public function update(Request $request, string $id)
    {
        //
    } */

    /**
     * Remove the specified resource from storage.
     */
  /*   public function destroy(string $id)
    {
        //
    } */
}
