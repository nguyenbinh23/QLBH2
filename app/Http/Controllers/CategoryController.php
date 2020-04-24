<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Http\Requests\CategoryRequest;

class CategoryController extends Controller
{
    public function all()
    {
        $categories = Category::all();

        return response()->json($categories);
    }
    public function list()
    {
        $categories = Category::paginate(5);

        return response()->json($categories);
    }
    public function add(CategoryRequest $request)
    {
        $category = new Category;
        $category->category_name = $request->category_name;
        $category->category_code = $request->category_code;
        $category->save();

        return response()->json($category,200);
    }
    public function index(Request $request)
    {
        $category = Category::find($request->id);
        return response()->json($category);
    }
    public function edit(CategoryRequest $request)
    {
        $category = Category::find($request->id);
        $category->category_name = $request->category_name;
        $category->category_code = $request->category_code;
        $category->save();

        return response()->json($category,200);
    }
    public function find(Request $request)
    {
        $categories = Category::where('category_name','like',"%$request->category_name%")->orWhere('category_code','like',"%$request->category_code%")->paginate(5);

        return response()->json($categories);
    }

    public function remove(Request $request)
    {
        $category = Category::find($request->id);
        $category->delete();

        return response()->json('Xóa thành công',200);
    }
}
