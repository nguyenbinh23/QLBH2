<?php

namespace App\Http\Controllers;

use App\PriceList;
use App\Order;
use App\OrderDetail;
use Carbon\Carbon;

use Illuminate\Http\Request;
class StatisticController extends Controller
{
    public function ThongKeNhapXuatTrongNgay($Date)
    {
        $chartData = [
            ['Ngày','Tổng nhập','Tổng xuất']
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
            "Ngày $day-$month-$year",
            $import_price,
            $sales_price,
        ];
        array_push($chartData,$array);

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
    public function ThongKeNhapXuatTrongKhoangThoiGian($fromDate , $toDate)
    {
        $chartData = [
            ['Ngày','Tổng nhập','Tổng xuất']
        ];
        $import_price = 0;
        $sales_price = 0;
        $fromDate = Carbon::parse($fromDate)->format('Y-m-d');
        $toDate = Carbon::parse($toDate)->format('Y-m-d');
            $orders = Order::where('id','>',0)
            ->whereDate('created_at','>=',$fromDate)
            ->whereDate('created_at','<=',$toDate)
            ->with('order_details')->get();

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
            $fromDateformat = Carbon::parse($fromDate)->format('d-m-Y');
            $toDateformat = Carbon::parse($toDate)->format('d-m-Y');
            $array =
            [
                "Từ ngày $fromDateformat đến $toDateformat",
                $import_price,
                $sales_price,
            ];
            array_push($chartData,$array);

        return $chartData;
    }

    public function profitAndLossStatement(Request $request)
    {
       $nhapxuat1thang = $this->ThongKeNhapXuat1Thang($request->date);
       $nhapxuattrongngay = $this->ThongKeNhapXuatTrongNgay($request->date);

       $nhapxuattrongkhoangthoigian = $this->ThongKeNhapXuatTrongKhoangThoiGian($request->from_date,$request->to_date);
       return response()->json([
           'nhapxuat1thang' => $nhapxuat1thang,
           'nhapxuattrongngay' => $nhapxuattrongngay,
           'nhapxuattrongkhoangthoigian' => $nhapxuattrongkhoangthoigian
       ]);
    }
    public function statisticProducts(Request $request)
    {
        ini_set("memory_limit", "-1");
        ini_set('max_execution_time', 480);
        $products = json_decode($request->selected);

        $products_finish = [];
        if(isset($products))
        {
            foreach($products as $product)
            {
                $orderDetailOfProduct = OrderDetail::where('product_name',$product)->get();
                foreach($orderDetailOfProduct as $item)
                {
                    if($item->order->kind == 'banhang')
                    {
                        $temp = [
                            'id' => $item->product_id,
                            'product_name' => $item->product_name,
                            'purchased_quantity' => 0,
                            'sold_quantity' => $item->quantity,
                        ];
                        if(count($products_finish) == 0)
                        {
                            array_push($products_finish,$temp);
                        }
                        elseif(count($products_finish) > 0)
                        {
                            foreach($products_finish as $product_find)
                            {
                                if($product_find['id'] == $temp['id'])
                                {
                                    $product_find['sold_quantity'] += $temp['sold_quantity'];
                                }
                                else
                                {
                                    array_push($products_finish,$temp);
                                }
                            }
                        }
                    }
                    else
                    {
                        $temp = [
                            'id' => $item->product_id,
                            'product_name' => $item->product_name,
                            'purchased_quantity' => $item->quantity,
                            'sold_quantity' => 0,
                        ];
                        if(count($products_finish) == 0)
                        {
                            array_push($products_finish,$temp);
                        }
                        elseif(count($products_finish) > 0)
                        {
                            foreach($products_finish as $product_find)
                            {
                                if($product_find['id'] == $temp['id'])
                                {
                                    $product_find['purchased_quantity'] += $temp['purchased_quantity'];
                                }
                                else
                                {
                                    array_push($products_finish,$temp);
                                }
                            }
                        }
                    }

                }
            }
        }
        return response()->json($products_finish);
    }
}
