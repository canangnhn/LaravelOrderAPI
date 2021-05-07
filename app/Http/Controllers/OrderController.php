<?php

namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\Customer;
use Illuminate\Http\Request;
class OrderController extends Controller
{
     public function newOrder(Request $request,$id){//customer_id
         $customer=Customer::find($id); 
         if($customer){
            $order=new Order();
            $order->orderCode=$request->orderCode;
            $order->productId=$request->productId;
            $order->quantity=$request->quantity;
            $order->address=$request->address;
            $order->shippingDate=$request->shippingDate;
            $order->customer_id=$id;
            $order->save();
            return response(['message'=>'Successfully!']);

         }
         else{
            return response(['message'=>'Customer not found!']);
         }
        
   
   
     }

     public function updateOrder(Request $request,$id){

        $customer=Customer::find($id); 
        if($customer){
            $order=Order::where('id',$request->orderId)->where('customer_id',$id)->first();
            if($order){
                $date=now();
                $datetime1 = strtotime($date); 
                $datetime2 = strtotime( $order->shippingDate);
                $diff = (int)(($datetime2 - $datetime1)/86400);
                if($diff>0){
                    Order::where('id',$request->orderId)->update(
                        ['shippingDate' => $request->newShippingDate]
                    );
    
                    return response(['message'=>'Successfully!']);
                }
                else{
                    return response(['message'=>'Shipping date expired!']);
                }

            }else{
                return response(['message'=>'Order not found!']);
            }
      

        }
        else{
            return response(['message'=>'Customer not found!']);
        }

     }

     public function searchOrder($orderCode,$id){
        $order=Order::where('customer_id',$id)
        ->where('orderCode', 'like', '%' . $orderCode. '%')->get();
        return $order;
     }

     public function listOrder($id){
     
         $order=Order::where('customer_id',$id)->get();
         return $order;
     }
}
