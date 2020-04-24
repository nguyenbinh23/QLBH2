<?php

namespace App\Http\Controllers;

use App\PriceList;
use App\Order;
use App\OrderDetail;
use Carbon\Carbon;

use Illuminate\Http\Request;
class StatisticController extends Controller
{
    public function LoiNhuanTrongNgay($Date)
    {
        $date = Carbon::parse($Date)->format('Y-m-d');

        $orders = Order::where('id','>',0)->whereDate('created_at',$date)->with('order_details')->get();
        $total_cost_price = 0;
        $total_sale_price = 0;
        $total_tax_price = 0;
        $total_profit = 0;
        $total_sales_price_with_tax = 0;
        foreach($orders as $order)
        {

            $order_cost_price = 0;
            $order_sales_price = 0;
            $order_profit = 0;
            foreach($order->order_details as $order_detail)
            {
                $Itemcost = PriceList::where('name','Giá nhập')->where('product_id',$order_detail->product_id)->first()->cost;
                $costprice = $order_detail->quantity * $Itemcost;
                $order_cost_price +=  $costprice;
                $order_sales_price += $order_detail->totalprice;
            }
            $order_sales_price = $order_sales_price - $order_sales_price * $order->discount /100;
            $tax_price =  $order_sales_price * $order->tax /100;
            $order_profit = $order_sales_price - $order_cost_price;


            //Tổng các cột giá trị của các hóa đơn
            $total_cost_price += $order_cost_price;
            $total_sale_price += $order_sales_price;
            $total_tax_price += $tax_price;
            $total_profit += $order_profit;
            $total_sales_price_with_tax += $order->total_price;
        }

        $array = [
                    [
                        'Ngày',
                        'Vốn',
                        'Bán ra (chưa thuế)',
                        'Bán ra (gồm thuế)',
                        'Thuế',
                        'Lợi nhuận'
                    ],
                    [
                        "$Date",
                        $total_cost_price,
                        $total_sale_price,
                        $total_sales_price_with_tax,
                        $total_tax_price,
                        $total_profit,
                    ]
                ];
        return $array;
    }
    public function LoiNhuanTrong1Thang($Date)
    {
        $dateInMonth = Carbon::parse($Date)->daysInMonth;
        $chartData = [
            ['Ngày','Lợi nhuận']
        ];
        for($i = 1; $i <= $dateInMonth; $i++)
        {
            $date = Carbon::parse($Date);
            $month = $date->month;
            $year = $date->year;

            $orders = Order::where('id','>',0)->whereDate('created_at',"$year-$month-$i")->with('order_details')->get();
            $total_cost_price = 0;
            $total_sale_price = 0;
            $total_tax_price = 0;
            $total_profit = 0;
            $total_sales_price_with_tax = 0;

            foreach($orders as $order)
            {

                $order_cost_price = 0;
                $order_sales_price = 0;
                $order_profit = 0;
                foreach($order->order_details as $order_detail)
                {
                    $Itemcost = PriceList::where('name','Giá nhập')->where('product_id',$order_detail->product_id)->first()->cost;
                    $costprice = $order_detail->quantity * $Itemcost;
                    $order_cost_price +=  $costprice;
                    $order_sales_price += $order_detail->totalprice;
                }
                $order_sales_price = $order_sales_price - $order_sales_price * $order->discount /100;
                $tax_price =  $order_sales_price * $order->tax /100;
                $order_profit = $order_sales_price - $order_cost_price;


                //Tổng các cột giá trị của các hóa đơn
                $total_cost_price += $order_cost_price;
                $total_sale_price += $order_sales_price;
                $total_tax_price += $tax_price;
                $total_profit += $order_profit;
                $total_sales_price_with_tax += $order->total_price;
            }
            $array =
                [
                    "$i",
                    $total_profit,
                ];
            array_push($chartData,$array);
        }
        return $chartData;
    }
    public function ThongKeNhapXuat1Thang($Date)
    {
        $dateInMonth = Carbon::parse($Date)->daysInMonth;
        $chartData = [
            ['Ngày','Tổng nhập','Tổng xuất']
        ];
        for($i = 1; $i <= $dateInMonth; $i++)
        {
            $date = Carbon::parse($Date);
            $month = $date->month;
            $year = $date->year;

            $import_price = 0;
            $sales_price = 0;

            $orders = Order::where('id','>',0)->whereDate('created_at',"$year-$month-$i")->with('order_details')->get();

            foreach($orders as $order)
            {
                if($order->kind == 'nhaphang')
                {
                    $import_price += $order->total_price;
                }
                else
                {
                    $sales_price += $order->total_price;
                }
            }
            $array =
                [
                    "$i",
                    $import_price,
                    $sales_price,
                ];
            array_push($chartData,$array);
        }
        return $chartData;
    }
    public function ThongKeNhapXuatTrongNgay($Date)
    {
        $chartData = [
            ['Ngày','Nhập vào','Bán ra']
        ];

            $date = Carbon::parse($Date);
            $day = $date->day;
            $month = $date->month;
            $year = $date->year;

            $import_price = 0;
            $sales_price = 0;

            $orders = Order::where('id','>',0)->whereDate('created_at',"$year-$month-$day")->with('order_details')->get();

            foreach($orders as $order)
            {
                if($order->kind == 'nhaphang')
                {
                    $import_price += $order->total_price;
                }
                else
                {
                    $sales_price += $order->total_price;
                }
            }
            $array =
                [
                    "$day-$month-$year",
                    $import_price,
                    $sales_price,
                ];
            array_push($chartData,$array);
        return $chartData;
    }
    public function profitAndLossStatement(Request $request)
    {
       $loinhuan1thang = $this->LoiNhuanTrong1Thang($request->date);
       $loinhuantrongngay = $this->LoiNhuanTrongNgay($request->date);
       $nhapxuat1thang = $this->ThongKeNhapXuat1Thang($request->date);
       $nhapxuattrongngay = $this->ThongKeNhapXuatTrongNgay($request->date);
       return response()->json([
           'loinhuantrongngay' => $loinhuantrongngay,
           'loinhuan1thang' => $loinhuan1thang,
           'nhapxuat1thang' => $nhapxuat1thang,
           'nhapxuattrongngay' => $nhapxuattrongngay
       ]);
    }
}
