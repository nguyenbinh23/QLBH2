<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;
use App\Http\Requests\CustomerRequest;
class CustomerController extends Controller
{
    public function all(Request $request)
    {

        $customers = Customer::all();
        return response()->json($customers);
    }
    public function index(Request $request)
    {
        $customer = Customer::find($request->id);
        return response()->json($customer);
    }
    public function add(CustomerRequest $request)
    {
        $customer = new Customer;

        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        $customer->address = $request->address;
        $customer->tax_code = $request->tax_code;
        $customer->sorting = $request->sorting;
        $customer->save();

        return response()->json($customer,200);
    }
    public function edit(CustomerRequest $request)
    {
        $customer = Customer::find($request->id);

        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        $customer->address = $request->address;
        $customer->tax_code = $request->tax_code;
        $customer->sorting = $request->sorting;
        $customer->save();

        return response()->json($customer,200);
    }
    public function remove(Request $request)
    {
        $customer = Customer::find($request->id);
        $customer->delete();
        return response()->json('Xóa thành công',200);
    }
    public function find(Request $request)
    {
        if($request->sorting == 'tatcakhachhang' && $request->name == null)
        {
            $customers = Customer::orderBy('created_at','desc')->paginate(5);
            return response()->json($customers);
        }
        else if($request->sorting == 'tatcakhachhang' && $request->name != null)
        {
            $customers = Customer::where('name','like',"%$request->name%")->orderBy('created_at','desc')->paginate(5);
            return response()->json($customers);
        }else
            $customers = Customer::where('sorting',$request->sorting)->where('name','like',"%$request->name%")->orderBy('created_at','desc')->paginate(5);
            return response()->json($customers);
    }
}
