<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Order;
use App\Product;
use Illuminate\Http\Request;
use App\OrderDetail;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $order = Order::where('id',$request->id)->with('order_details')->first();
        $customer = Customer::where('id',$order->customer_id)->first();
        if(isset($customer)){
            return response()->json([
                'order' => $order,
                'customer' => $customer
            ]);
        }else {
            return response()->json([
                'order' => $order,
                'customer' => null
            ]);
        }
    }
    public function addWithCustomer(Request $request)
    {
        $customer = Customer::find($request->customer_id);

        $order = new Order;
        $order->kind = $request->kind;
        $order->customer_id = $customer->id;
        $order->name = $customer->name;
        $order->address =  $customer->address;
        $order->phone = $customer->phone;
        $order->tax_code =  $customer->tax_code;
        $order->discount = $request->discount;
        $order->tax = $request->tax;
        $order->total_price = $request->totalPriceWithoutTaxAndDiscount;
        $order->save();
        if($request->kind == 'nhaphang')
            foreach(json_decode($request->carts) as $item)
            {
                $order_detail = new OrderDetail;
                $order_detail->product_id = $item->id;
                $order_detail->order_id = $order->id;
                $order_detail->product_name = $item->name;
                $order_detail->product_code = $item->code;
                $order_detail->quantity = $item->quantity;
                $order_detail->unit = $item->unit;
                $order_detail->price = $item->price;
                $order_detail->image = $item->image;
                $order_detail->discount = $item->discount;
                $order_detail->totalprice = $item->totalprice;
                $order_detail->save();

                $product = Product::find($item->id);
                $product->quantity += $item->quantity;
                $product->save();
            }
        else{
            foreach(json_decode($request->carts) as $item)
            {
                $order_detail = new OrderDetail;
                $order_detail->product_id = $item->id;
                $order_detail->order_id = $order->id;
                $order_detail->product_name = $item->name;
                $order_detail->product_code = $item->code;
                $order_detail->quantity = $item->quantity;
                $order_detail->unit = $item->unit;
                $order_detail->price = $item->price;
                $order_detail->image = $item->image;
                $order_detail->discount = $item->discount;
                $order_detail->totalprice = $item->totalprice;
                $order_detail->save();

                $product = Product::find($item->id);
                $product->quantity -= $item->quantity;
                $product->save();

            }
        }
        $orderWithOrderDetails = Order::where('id',$order->id)->with('order_details')->first();
        $totalPrice = 0;
        foreach($orderWithOrderDetails->order_details as $item)
        {
            $totalPrice += $item->totalprice;
        }
        $tax = $totalPrice * $orderWithOrderDetails->tax / 100;
        return response()->json([
            'order'       => $orderWithOrderDetails,
            'totalPrice' => $totalPrice,
            'tax'         => $tax,
            'totalPriceAfterTax' => $orderWithOrderDetails->total_price
        ]);
    }
    public function addWithNewCustomer(Request $request)
    {
        $customer = new Customer;
        $customer->name = $request->customer_name;
        $customer->tax_code = $request->tax_code;
        $customer->save();

        $order = new Order;
        $order->kind = $request->kind;
        $order->customer_id = $customer->id;
        $order->name = $customer->name;
        $order->address =  $customer->address;
        $order->phone = $customer->phone;
        $order->discount = $request->discount;
        $order->tax = $request->tax;
        $order->tax_code = $request->tax_code;
        $order->total_price = $request->totalPriceWithoutTaxAndDiscount;
        $order->save();
        if($request->kind == 'nhaphang')
            foreach(json_decode($request->carts) as $item)
            {
                $order_detail = new OrderDetail;
                $order_detail->product_id = $item->id;
                $order_detail->order_id = $order->id;
                $order_detail->product_name = $item->name;
                $order_detail->product_code = $item->code;
                $order_detail->quantity = $item->quantity;
                $order_detail->image = $item->image;
                $order_detail->unit = $item->unit;
                $order_detail->price = $item->price;
                $order_detail->discount = $item->discount;
                $order_detail->totalprice = $item->totalprice;
                $order_detail->save();

                $product = Product::find($item->id);
                $product->quantity += $item->quantity;
                $product->save();

            }
        else{
            foreach(json_decode($request->carts) as $item)
            {
                $order_detail = new OrderDetail;
                $order_detail->product_id = $item->id;
                $order_detail->order_id = $order->id;
                $order_detail->product_name = $item->name;
                $order_detail->product_code = $item->code;
                $order_detail->quantity = $item->quantity;
                $order_detail->image = $item->image;
                $order_detail->unit = $item->unit;
                $order_detail->price = $item->price;
                $order_detail->discount = $item->discount;
                $order_detail->totalprice = $item->totalprice;
                $order_detail->save();

                $product = Product::find($item->id);
                $product->quantity -= $item->quantity;
                $product->save();

            }
        }
        $orderWithOrderDetails = Order::where('id',$order->id)->with('order_details')->first();
        $totalPrice = 0;
        foreach($orderWithOrderDetails->order_details as $item)
        {
            $totalPrice += $item->totalprice;
        }
        $tax = $totalPrice * $orderWithOrderDetails->tax / 100;
        return response()->json([
            'order'       => $orderWithOrderDetails,
            'totalPrice' => $totalPrice,
            'tax'         => $tax,
            'totalPriceAfterTax' => $orderWithOrderDetails->total_price
        ]);
    }
    public function addWithoutCreateNewCustomer(Request $request)
    {
        $order = new Order;
        $order->kind = $request->kind;
        $order->name = $request->customer_name;
        $order->discount = $request->discount;
        $order->tax = $request->tax;
        $order->tax_code = $request->tax_code;
        $order->total_price = $request->totalPriceWithoutTaxAndDiscount;
        $order->save();
        if($request->kind == 'nhaphang')
            foreach(json_decode($request->carts) as $item)
            {
                $order_detail = new OrderDetail;
                $order_detail->product_id = $item->id;
                $order_detail->order_id = $order->id;
                $order_detail->product_name = $item->name;
                $order_detail->product_code = $item->code;
                $order_detail->quantity = $item->quantity;
                $order_detail->image = $item->image;
                $order_detail->unit = $item->unit;
                $order_detail->price = $item->price;
                $order_detail->discount = $item->discount;
                $order_detail->totalprice = $item->totalprice;
                $order_detail->save();

                $product = Product::find($item->id);
                $product->quantity += $item->quantity;
                $product->save();

            }
        else{
            foreach(json_decode($request->carts) as $item)
            {
                $order_detail = new OrderDetail;
                $order_detail->product_id = $item->id;
                $order_detail->order_id = $order->id;
                $order_detail->product_name = $item->name;
                $order_detail->product_code = $item->code;
                $order_detail->quantity = $item->quantity;
                $order_detail->unit = $item->unit;
                $order_detail->image = $item->image;
                $order_detail->price = $item->price;
                $order_detail->discount = $item->discount;
                $order_detail->totalprice = $item->totalprice;
                $order_detail->save();

                $product = Product::find($item->id);
                $product->quantity -= $item->quantity;
                $product->save();

            }
        }
        $orderWithOrderDetails = Order::where('id',$order->id)->with('order_details')->first();
        $totalPrice = 0;
        foreach($orderWithOrderDetails->order_details as $item)
        {
            $totalPrice += $item->totalprice;
        }
        $tax = $totalPrice * $orderWithOrderDetails->tax / 100;
        return response()->json([
            'order'       => $orderWithOrderDetails,
            'totalPrice' => $totalPrice,
            'tax'         => $tax,
            'totalPriceAfterTax' => $orderWithOrderDetails->total_price
        ]);
    }
    public function editWithNewCustomer(Request $request)
    {
        $customer = new Customer;
        $customer->name = $request->customer_name;
        $customer->tax_code = $request->tax_code;
        $customer->save();

        $order = Order::where('id',$request->id)->with('order_details')->first();

        if($order->kind == 'banhang')
        {
            foreach($order->order_details as $item)
            {
                $product = Product::find($item->product_id);
                if(isset($product))
                {
                    $product->quantity += $item->quantity;
                    $product->save();

                }
                OrderDetail::where('id',$item->id)->delete();
            }
        }else {
            foreach($order->order_details as $item)
            {
                $product = Product::find($item->product_id);
                if(isset($product))
                {
                    $product->quantity -= $item->quantity;
                    $product->save();


                }
                OrderDetail::where('id',$item->id)->delete();
            }
        }

        $order->kind = $request->kind;
        $order->customer_id = $customer->id;
        $order->name = $customer->name;
        $order->address =  $customer->address;
        $order->phone = $customer->phone;
        $order->discount = $request->discount;
        $order->tax = $request->tax;
        $order->tax_code = $request->tax_code;
        $order->total_price = $request->totalPriceWithoutTaxAndDiscount;
        $order->save();
        if($request->kind == 'nhaphang')
            foreach(json_decode($request->carts) as $item)
            {



                $order_detail = new OrderDetail;
                $order_detail->product_id = $item->product_id;
                $order_detail->order_id = $order->id;
                $order_detail->product_name = $item->product_name;
                $order_detail->product_code = $item->product_code;
                $order_detail->quantity = $item->quantity;
                $order_detail->unit = $item->unit;
                $order_detail->price = $item->price;
                $order_detail->image = $item->image;
                $order_detail->discount = $item->discount;
                $order_detail->totalprice = $item->totalprice;
                $order_detail->save();

                $product = Product::find($item->product_id);
                $product->quantity += $item->quantity;
                $product->save();

            }
        else{
            foreach(json_decode($request->carts) as $item)
            {


                $order_detail = new OrderDetail;
                $order_detail->product_id = $item->product_id;
                $order_detail->order_id = $order->id;
                $order_detail->product_name = $item->product_name;
                $order_detail->product_code = $item->product_code;
                $order_detail->quantity = $item->quantity;
                $order_detail->unit = $item->unit;
                $order_detail->price = $item->price;
                $order_detail->image = $item->image;
                $order_detail->discount = $item->discount;
                $order_detail->totalprice = $item->totalprice;
                $order_detail->save();

                $product = Product::find($item->product_id);
                $product->quantity -= $item->quantity;
                $product->save();


            }
        }

        $order_new = Order::where('id',$request->id)->with('order_details')->first();
        $totalPrice = 0;
        foreach($order_new->order_details as $item)
        {
            $totalPrice += $item->totalprice;
        }
        $tax = $totalPrice * $order_new->tax / 100;
        return response()->json([
            'order'       => $order_new,
            'totalPrice' => $totalPrice,
            'tax'         => $tax,
            'totalPriceAfterTax' => $order_new->total_price
        ]);
    }
    public function editWithCustomer(Request $request)
    {
        $customer = Customer::find($request->customer_id);

        $order = Order::where('id',$request->id)->with('order_details')->first();

        if($order->kind == 'banhang')
        {
            foreach($order->order_details as $item)
            {
                $product = Product::find($item->product_id);
                if(isset($product))
                {
                    $product->quantity += $item->quantity;
                    $product->save();


                }
                OrderDetail::where('id',$item->id)->delete();
            }
        }else {
            foreach($order->order_details as $item)
            {
                $product = Product::find($item->product_id);
                if(isset($product))
                {
                    $product->quantity -= $item->quantity;
                    $product->save();


                }
                OrderDetail::where('id',$item->id)->delete();
            }
        }

        $order->kind = $request->kind;
        $order->customer_id = $customer->id;
        $order->name = $customer->name;
        $order->address =  $customer->address;
        $order->phone = $customer->phone;
        $order->discount = $request->discount;
        $order->tax = $request->tax;
        $order->tax_code = $request->tax_code;
        $order->total_price = $request->totalPriceWithoutTaxAndDiscount;
        $order->save();
        if($request->kind == 'nhaphang')
            foreach(json_decode($request->carts) as $item)
            {


                $order_detail = new OrderDetail;
                $order_detail->product_id = $item->product_id;
                $order_detail->order_id = $order->id;
                $order_detail->product_name = $item->product_name;
                $order_detail->product_code = $item->product_code;
                $order_detail->quantity = $item->quantity;
                $order_detail->unit = $item->unit;
                $order_detail->price = $item->price;
                $order_detail->image = $item->image;
                $order_detail->discount = $item->discount;
                $order_detail->totalprice = $item->totalprice;
                $order_detail->save();

                $product = Product::find($item->product_id);
                $product->quantity += $item->quantity;
                $product->save();
            }
        else{
            foreach(json_decode($request->carts) as $item)
            {


                $order_detail = new OrderDetail;
                $order_detail->product_id = $item->product_id;
                $order_detail->order_id = $order->id;
                $order_detail->product_name = $item->product_name;
                $order_detail->product_code = $item->product_code;
                $order_detail->quantity = $item->quantity;
                $order_detail->unit = $item->unit;
                $order_detail->price = $item->price;
                $order_detail->image = $item->image;
                $order_detail->discount = $item->discount;
                $order_detail->totalprice = $item->totalprice;
                $order_detail->save();

                $product = Product::find($item->product_id);
                $product->quantity -= $item->quantity;
                $product->save();
            }
        }

        $order_new = Order::where('id',$request->id)->with('order_details')->first();
        $totalPrice = 0;
        foreach($order_new->order_details as $item)
        {
            $totalPrice += $item->totalprice;
        }
        $tax = $totalPrice * $order_new->tax / 100;
        return response()->json([
            'order'       => $order_new,
            'totalPrice' => $totalPrice,
            'tax'         => $tax,
            'totalPriceAfterTax' => $order_new->total_price
        ]);
    }
    public function editWithoutCreateNewCustomer(Request $request)
    {
        $order = Order::where('id',$request->id)->with('order_details')->first();

        if($order->kind == 'banhang')
        {
            foreach($order->order_details as $item)
            {
                $product = Product::find($item->product_id);
                if(isset($product))
                {
                    $product->quantity += $item->quantity;
                    $product->save();


                }
                OrderDetail::where('id',$item->id)->delete();
            }
        }else {
            foreach($order->order_details as $item)
            {
                $product = Product::find($item->product_id);
                if(isset($product))
                {
                    $product->quantity -= $item->quantity;
                    $product->save();


                }
                OrderDetail::where('id',$item->id)->delete();
            }
        }

        $order->kind = $request->kind;
        $order->name = $request->customer_name;
        $order->discount = $request->discount;
        $order->tax = $request->tax;
        $order->tax_code = $request->tax_code;
        $order->total_price = $request->totalPriceWithoutTaxAndDiscount;
        $order->save();
        if($request->kind == 'nhaphang')
            foreach(json_decode($request->carts) as $item)
            {


                $order_detail = new OrderDetail;
                $order_detail->product_id = $item->product_id;
                $order_detail->order_id = $order->id;
                $order_detail->product_name = $item->product_name;
                $order_detail->product_code = $item->product_code;
                $order_detail->quantity = $item->quantity;
                $order_detail->unit = $item->unit;
                $order_detail->price = $item->price;
                $order_detail->image = $item->image;
                $order_detail->discount = $item->discount;
                $order_detail->totalprice = $item->totalprice;
                $order_detail->save();

                $product = Product::find($item->product_id);
                $product->quantity += $item->quantity;
                $product->save();
            }
        else{
            foreach(json_decode($request->carts) as $item)
            {


                $order_detail = new OrderDetail;
                $order_detail->product_id = $item->product_id;
                $order_detail->order_id = $order->id;
                $order_detail->product_name = $item->product_name;
                $order_detail->product_code = $item->product_code;
                $order_detail->quantity = $item->quantity;
                $order_detail->unit = $item->unit;
                $order_detail->price = $item->price;
                $order_detail->image = $item->image;
                $order_detail->discount = $item->discount;
                $order_detail->totalprice = $item->totalprice;
                $order_detail->save();

                $product = Product::find($item->product_id);
                $product->quantity -= $item->quantity;
                $product->save();
            }
        }

        $order_new = Order::where('id',$request->id)->with('order_details')->first();
        $totalPrice = 0;
        foreach($order_new->order_details as $item)
        {
            $totalPrice += $item->totalprice;
        }
        $tax = $totalPrice * $order_new->tax / 100;
        return response()->json([
            'order'       => $order_new,
            'totalPrice' => $totalPrice,
            'tax'         => $tax,
            'totalPriceAfterTax' => $order_new->total_price
        ]);
    }
    public function find(Request $request)
    {
        if($request->kind == 'chuacokh')
        {
            $orders = Order::where('name',null)->with('order_details')->orderBy('id','desc')->paginate(5);
            return response()->json($orders);
        }else {
            if($request->kind == 'tatcakhachhang' && $request->name == null)
            {
                $orders = Order::with('order_details')->orderBy('id','desc')->paginate(5);
                return response()->json($orders);
            }
            if($request->kind == 'tatcakhachhang' && $request->name != null)
            {
                $orders = Order::where('name','like',"%$request->name%")->with('order_details')->orderBy('id','desc')->paginate(5);
                return response()->json($orders);
            }
            if($request->kind == 'nhaphang' && $request->name == null)
            {
                $orders = Order::where('kind',$request->kind)->with('order_details')->orderBy('id','desc')->paginate(5);
                return response()->json($orders);
            }
            if($request->kind == 'nhaphang' && $request->name != null)
            {
                $orders = Order::where('kind',$request->kind)->where('name','like',"%$request->name%")->orderBy('id','desc')->with('order_details')->paginate(5);
                return response()->json($orders);
            }
            if($request->kind == 'banhang' && $request->name == null)
            {
                $orders = Order::where('kind',$request->kind)->with('order_details')->orderBy('id','desc')->paginate(5);
                return response()->json($orders);
            }
            if($request->kind == 'banhang' && $request->name != null)
            {
                $orders = Order::where('kind',$request->kind)->where('name','like',"%$request->name%")->orderBy('id','desc')->with('order_details')->paginate(5);
                return response()->json($orders);
            }
        }
    }
    public function remove(Request $request)
    {
        $order = Order::where('id',$request->id)->with('order_details')->first();

        if($order->kind == 'nhaphang')
            foreach($order->order_details as $item)
            {
                $product = Product::find($item->product_id);
                if(isset($product))
                {
                    $product->quantity -= $item->quantity;
                    $product->save();


                }
            }
        if($order->kind == 'banhang')
        {
            foreach($order->order_details as $item)
            {
                $product = Product::find($item->product_id);
                if(isset($product))
                {
                    $product->quantity += $item->quantity;
                    $product->save();
                }
            }
        }
        OrderDetail::where('order_id',$order->id)->delete();
        $order->delete();
        return response()->json('Xóa thành công',200);
    }
}
