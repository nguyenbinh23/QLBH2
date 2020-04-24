<?php

namespace App\Http\Controllers;

use App\Unit;
use Illuminate\Http\Request;
use App\Http\Requests\UnitRequest;

class UnitController extends Controller
{
    public function all()
    {
        $units = Unit::all();

        return response()->json($units);
    }
    public function add(UnitRequest $request)
    {
        $unit = new Unit;
        $unit->name = $request->name;
        $unit->code = $request->code;
        $unit->save();

        return response()->json($unit,200);
    }
    public function index(Request $request)
    {
        $unit = Unit::find($request->id);
        return response()->json($unit);
    }
    public function edit(UnitRequest $request)
    {
        $unit = Unit::find($request->id);
        $unit->name = $request->name;
        $unit->code = $request->code;
        $unit->save();

        return response()->json($unit,200);
    }
    public function find(Request $request)
    {
        $units = Unit::where('name','like',"%$request->name%")->orWhere('code','like',"%$request->code%")->paginate(5);

        return response()->json($units);
    }

    public function remove(Request $request)
    {
        $unit = Unit::find($request->id);
        $unit->delete();

        return response()->json('Xóa thành công',200);
    }
}
