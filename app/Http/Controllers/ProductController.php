<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Imports\ProductImport;
use Illuminate\Http\Request;
use App\Product;
use App\Category;
use App\PriceList;
use App\Unit;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
class ProductController extends Controller
{
    public function all(){
        $products = Product::all();
        return response()->json($products);
    }

    public function index(Request $request)
    {
        $product = Product::where('id',$request->id)->with('pricelist')->first();
        return response()->json($product);
    }
    public function getQuantity(Request $request)
    {
        $quantity = Product::where('code',$request->code)->first()->quantity;

        return response()->json($quantity);
    }
    public function add(ProductRequest $request)
    {
        $product_name = $request->name;
        $product_code = $request->code;
        $product_description = $request->description;
        $product_unit_id = $request->unit_id;
        $product_quantity = $request->quantity;
        $product_cate_id = $request->category_id;
        $product_price_types = json_decode($request->price_types);
        $files = $request->file('files');

        $image = 'noimage.jpg';
        $thumbnail = 'noimage.jpg';
        $listFile = array();

        if($request->hasFile('product_image')){
            // Get filename with the extension
            $filenameWithExt1 = $request->file('product_image')->getClientOriginalName();
            // Get just filename
            $filename1 = pathinfo($filenameWithExt1, PATHINFO_FILENAME);
            //Phần mở rộng của file(Đuôi)
            $extension1 = $request->file('product_image')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore1 = $filename1.'_'.time().'.'.$extension1;

            $image = $fileNameToStore1;

            // Upload Image
            $request->file('product_image')->storeAs('public/cover_images',$fileNameToStore1);
        }

        if($files != null){
            foreach($files as $file){
                // Get filename with the extension
                $filenameWithExt1 = $file->getClientOriginalName();
                // Get just filename
                $filename = pathinfo($filenameWithExt1, PATHINFO_FILENAME);
                //Phần mở rộng của file(Đuôi)
                $extension = $file->getClientOriginalExtension();
                // Filename to store
                $fileNameToStore = $filename.'_'.time().'.'.$extension;
                array_push($listFile,$fileNameToStore);
                $file->storeAs('public/cover_images',$fileNameToStore);
            }
        }
        else{
            $noimage = 'noimage.jpg';
            array_push($listFile,$noimage);
        }

        $unit_name = Unit::find($product_unit_id)->name;

        $product = new Product;
        $product->name = $product_name;
        $product->code = $product_code;
        $product->description = $product_description;
        $product->unit_id = $product_unit_id;
        $product->unit = $unit_name;
        $product->image = $image;
        $product->thumbnail = $thumbnail;
        $product->image_list = json_encode($listFile);
        $product->quantity = $product_quantity;
        $product->category_id = $product_cate_id;

        $product->save();

        $id = $product->id;

        foreach($product_price_types as $item){
            $price = new PriceList;
            $price->name = $item->name;
            $price->cost = $item->cost;
            $price->product_id = $id;
            $price->save();
        }


        return response()->json($product);
    }

    public function edit(ProductRequest $request)
    {
        $product = Product::where('id',$request->id)->with('pricelist')->first();
        if($request->picked == 'hinhanhmoi'){
            $product_name = $request->name;
            $product_code = $request->code;
            $product_description = $request->description;
            $product_unit_id = $request->unit_id;
            $product_quantity = $request->quantity;
            $product_cate_id = $request->category_id;
            $product_price_types = json_decode($request->price_types);
            $product_old_image_list = json_decode($request->old_image_list);
            $product_delete_images = json_decode($request->delete_images);
            $product_old_image = $request->old_image;
            $files = null;

            if($request->hasFile('files')){
                $files = $request->file('files');
            }

            $listFile = [];
            if(!empty($product_old_image_list)){
                foreach($product_old_image_list as $item){
                    array_push($listFile,$item);
                }
            }

            $image = 'noimage.jpg';
            $thumbnail = 'noimage.jpg';
            if($request->hasFile('product_image')){
                // Get filename with the extension
                $filenameWithExt1 = $request->file('product_image')->getClientOriginalName();
                // Get just filename
                $filename1 = pathinfo($filenameWithExt1, PATHINFO_FILENAME);
                //Phần mở rộng của file(Đuôi)
                $extension1 = $request->file('product_image')->getClientOriginalExtension();
                // Filename to store
                $fileNameToStore1 = $filename1.'_'.time().'.'.$extension1;
                $image = $fileNameToStore1;
                // Upload Image
                $request->file('product_image')->storeAs('public/cover_images',$fileNameToStore1);
                //Xoa image cu
                if($product_old_image != 'noimage.jpg'){
                    Storage::delete('public/cover_images/'.$product_old_image);
                }
            }else {
                $image = $product_old_image;
            }

            if(isset($files)){
                foreach($files as $file){
                    // Get filename with the extension
                    $filenameWithExt1 = $file->getClientOriginalName();
                    // Get just filename
                    $filename = pathinfo($filenameWithExt1, PATHINFO_FILENAME);
                    //Phần mở rộng của file(Đuôi)
                    $extension = $file->getClientOriginalExtension();
                    // Filename to store
                    $fileNameToStore = $filename.'_'.time().'_list.'.$extension;
                    array_push($listFile,$fileNameToStore);
                    $file->storeAs('public/cover_images',$fileNameToStore);
                }
            }


            $old_image = $product->image;

            if($old_image != 'noimage.jpg' && $old_image != $product_old_image)
            {
                Storage::delete('public/cover_images/'.$old_image);
            }
            if(isset($product_delete_images)){
                foreach($product_delete_images as $item){
                    Storage::delete('public/cover_images/'.$item);
                }
            }

            $unit_name = Unit::find($product_unit_id)->name;
            $product->name = $product_name;
            $product->code = $product_code;
            $product->description = $product_description;
            $product->unit_id = $product_unit_id;
            $product->unit = $unit_name;
            $product->image = $image;
            $product->thumbnail = $thumbnail;
            $product->image_list = json_encode($listFile);
            $product->quantity = $product_quantity;
            $product->category_id = $product_cate_id;

            $product->save();

            $id = $product->id;

            PriceList::where('product_id',$id)->delete();
            foreach($product_price_types as $item){
                $price = new PriceList;
                $price->name = $item->name;
                $price->cost = $item->cost;
                $price->product_id = $id;
                $price->save();
            }
            return response()->json($product,200);
        }else{
            $unit_name = Unit::find($request->unit_id)->name;

            $product->name = $request->name;
            $product->code = $request->code;
            $product->description = $request->description;
            $product->unit_id = $request->unit_id;
            $product->unit = $unit_name;
            $product->thumbnail = 'noimage.jpg';
            $product->quantity = $request->quantity;
            $product->category_id = $request->category_id;

            $product->save();

            $id = $product->id;

            PriceList::where('product_id',$id)->delete();
            foreach(json_decode($request->price_types) as $item){
                $price = new PriceList;
                $price->name = $item->name;
                $price->cost = $item->cost;
                $price->product_id = $id;
                $price->save();
            }
            return response()->json($product,200);
        }
    }
    public function find(Request $request)
    {
        if($request->product_category == 'tatcatheloai' && $request->name == null){
            $products = Product::orderBy('created_at','desc')
            ->with(['pricelist' => function($query){
                $query->where(function($q){
                    $q->where('name','Giá bán lẻ')->get();
                    $q->orWhere('name','Giá bán sỉ')->get();
                });
            }])
            ->paginate(5);
            foreach($products as $item){
                if(isset($item->pricelist))
                {
                    foreach($item->pricelist as $price)
                    {
                        if($price->name == 'Giá bán lẻ')
                        {
                            $item['price'] = $price->cost;
                        }
                        else if($price->name == 'Giá bán sỉ')
                        {
                            $item['price'] = $price->cost;
                        }
                        else {
                            if(isset($item->pricelist[0])){
                                $item['price'] = $item->pricelist[0]->cost;
                            }else {
                                $item['price'] = 0;
                            }
                        }
                    }
                }
            }
            return response()->json($products);
        }
        else if($request->product_category == 'tatcatheloai' && $request->name != null){
            $products = Product::where('name','like',"%$request->name%")
            ->orderBy('created_at','desc')
            ->with(['pricelist' => function($query){
                $query->where(function($q){
                    $q->where('name','Giá bán lẻ')->get();
                    $q->orWhere('name','Giá bán sỉ')->get();
                });
            }])
            ->paginate(5);
            foreach($products as $item){
                if(isset($item->pricelist))
                {
                    foreach($item->pricelist as $price)
                    {
                        if($price->name == 'Giá bán lẻ')
                        {
                            $item['price'] = $price->cost;
                        }
                        else if($price->name == 'Giá bán sỉ')
                        {
                            $item['price'] = $price->cost;
                        }
                        else {
                            if(isset($item->pricelist[0])){
                                $item['price'] = $item->pricelist[0]->cost;
                            }else {
                                $item['price'] = 0;
                            }
                        }
                    }
                }
            }

            return response()->json($products);
        }
        else if($request->product_category != 'tatcatheloai' && $request->name == null){
            $products = Product::where('category_id',$request->product_category)
            ->orderBy('created_at','desc')
            ->with(['pricelist' => function($query){
                $query->where(function($q){
                    $q->where('name','Giá bán lẻ')->get();
                    $q->orWhere('name','Giá bán sỉ')->get();
                });
            }])
            ->paginate(5);
            foreach($products as $item){
                if(isset($item->pricelist))
                {
                    foreach($item->pricelist as $price)
                    {
                        if($price->name == 'Giá bán lẻ')
                        {
                            $item['price'] = $price->cost;
                        }
                        else if($price->name == 'Giá bán sỉ')
                        {
                            $item['price'] = $price->cost;
                        }
                        else {
                            if(isset($item->pricelist[0])){
                                $item['price'] = $item->pricelist[0]->cost;
                            }else {
                                $item['price'] = 0;
                            }
                        }
                    }
                }
            }
            return response()->json($products);
        }
        else if($request->product_category != 'tatcatheloai' && $request->name != null){
            $products = Product::where('category_id',$request->product_category)
            ->where('name','like',"%$request->name%")
            ->orderBy('created_at','desc')
            ->with(['pricelist' => function($query){
                $query->where(function($q){
                    $q->where('name','Giá bán lẻ')->get();
                    $q->orWhere('name','Giá bán sỉ')->get();
                });
            }])
            ->paginate(5);
            foreach($products as $item){
                if(isset($item->pricelist))
                {
                    foreach($item->pricelist as $price)
                    {
                        if($price->name == 'Giá bán lẻ')
                        {
                            $item['price'] = $price->cost;
                        }
                        else if($price->name == 'Giá bán sỉ')
                        {
                            $item['price'] = $price->cost;
                        }
                        else {
                            if(isset($item->pricelist[0])){
                                $item['price'] = $item->pricelist[0]->cost;
                            }else {
                                $item['price'] = 0;
                            }
                        }
                    }
                }
            }
            return response()->json($products);
        }
    }
    public function remove(Request $request)
    {
        $product = Product::find($request->id);
        $image = $product->image;
        if($image != 'noimage.jpg')
        {
            Storage::delete('public/cover_images/'.$image);
        }


        $images = json_decode($product->image_list);
        if(isset($images)){
            foreach($images as $item){
                if($item != 'noimage.jpg')
                {
                    Storage::delete('public/cover_images/'.$item);

                }
            }
        }
        $product->delete();
        PriceList::where('product_id',$request->id)->delete();

        return response()->json('Xóa thành công',200);
    }
    public function import(Request $request)
    {
            $this->validate($request,
                [
                    'file' => 'required|mimes:xlsx,xls'
                ],
                [
                    'file.required' => 'Cần gửi dữ liệu (file)',
                    'file.mimes' => 'File phải có phần mở rộng là *.xlsx'
                ]
            );
        $import_data = Excel::toArray(new ProductImport, request()->file('file'));
        if(isset($import_data)){

            foreach($import_data[0] as $key => $item){
                if($key > 0)
                {
                    Validator::make($item,
                    [
                        0 => 'required',
                        1 => 'required|unique:products,code',
                        3 => 'required',
                        4 => 'required',
                        5 => 'required',
                        6 => 'required',
                        7 => 'required',
                        8 => 'required'
                    ],
                    [
                        '0.required' => 'Không được bỏ trống tên sản phẩm',
                        '1.unique' => 'Trong danh sách import có mã sản phẩm đang bị trùng',
                        '3.required' => 'Không được bỏ trống tên mã đơn vị sản phẩm',
                        '4.required' => 'Không được bỏ trống số lượng sản phẩm',
                        '5.required' => 'Không được bỏ trống mã thể loại sản phẩm',
                        '6.required' => 'Không được bỏ trống giá nhập',
                        '7.required' => 'Không được bỏ trống giá bán sỉ',
                        '8.required' => 'Không được bỏ trống giá bán lẻ',
                    ])->validate();

                    $id_category = Category::where('category_code',$item[5])->first()->id;
                    $unit = Unit::where('code',$item[3])->first();

                    $product = new Product;
                    $product->name = $item[0];
                    $product->code = $item[1];
                    $product->description = $item[2];
                    $product->unit = $unit->name;
                    $product->unit_id = $unit->id;
                    $product->quantity = $item[4];
                    $product->category_id = intval($id_category);
                    $product->save();


                    $price1 = new PriceList;
                    $price1->name = 'Giá nhập';
                    $price1->cost = floatval($item[6]);
                    $price1->product_id = $product->id;
                    $price1->save();

                    $price2 = new PriceList;
                    $price2->name = 'Giá bán sỉ';
                    $price2->cost = floatval($item[7]);
                    $price2->product_id = $product->id;
                    $price2->save();

                    $price3 = new PriceList;
                    $price3->name = 'Giá bán lẻ';
                    $price3->cost = floatval($item[8]);
                    $price3->product_id = $product->id;
                    $price3->save();
                }
            }
            return response()->json($import_data[0]);
        }
    }
}

