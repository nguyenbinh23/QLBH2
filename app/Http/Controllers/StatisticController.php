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
            "$day-$month-$year",
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
            $array =
            [
                "Từ ngày $fromDate đến $toDate",
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
}
