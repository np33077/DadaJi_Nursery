<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Plant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlantController extends Controller
{
    public function add(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "plant_name"      => 'required',
            "variety"         => 'required',
            "sowing_duration" => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json(["message" => $validate->errors()->first()], 400);
        }

        $data = new Plant();
        $data->plant_name      = $request->plant_name;
        $data->variety         = $request->variety;
        $data->sowing_duration = $request->sowing_duration;
        $data->created_at = Carbon::now();
        $data->save();
        return response()->json(['message' => trans('messages.ADDED_SUCESSFULLY', ['title' =>  'Plant'])], 200);
    }

    public function edit(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "id" => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json(["message" => $validate->errors()->first()], 400);
        }
        $query  = Plant::where('id', $request->id)->first();
        if (!empty($query)) {
            $query->plant_name      = $request->plant_name ?? $query->plant_name;
            $query->variety         = $request->variety ?? $query->variety;
            $query->sowing_duration = $request->sowing_duration ?? $query->sowing_duration;
            $query->updated_at      = Carbon::now();
            $query->save();
            return response()->json(['message' => trans('messages.UPDATE_SUCESSFULLY', ['title' => 'Plant'])], 200);
        } else {
            return response()->json(['message' => trans('messages.SOMETHING_WENT_WRONG')], 400);
        }
    }

    public function details($id)
    {
        $result = Plant::where('id', '=', $id)->first();
        if (!empty($result)) {
            return response()->json(['status' => 'success', 'result' => $result], 200);
        } else {
            return response()->json(['status' => 'error', 'message' => trans("messages.NO_RECORD_FOUND")], 400);
        }
    }

    public function updateStatus(Request $request)
    {
        $query = Plant::where('id', $request->id)->first();
        if (!empty($query)) {
            if ($query->status == 'Y') {
                $query->status = 'N';
            } else {
                $query->status = 'Y';
            }
            $query->save();
            return response()->json(['message' => trans('messages.STATUS_UPDATED')], 200);
        } else {
            return response()->json(['status' => 'error', 'message' => trans("messages.NO_RECORD_FOUND")], 400);
        }
    }

    public function paginationlist(Request $request)
    {
        $offset     = (int) trim($request->input('offset'));
        $limit      = (int) trim($request->input('limit'));
        $index      = Helper::getIndexWithLimit($offset, $limit);
        $list       = Plant::list($index, $limit, $request);
        $count      = Plant::list(-1, $limit, $request);
        $nextOffset = Helper::getWithLimitNextOffset($offset, count($list), $limit);
        return response()->json(['count' => $count, 'next_offset' => $nextOffset, 'list' => $list ?? []], 200);
    }

    public function dropdownlist()
    {
        $plantlist = Plant::Dropdownlist();
        if($plantlist->isEmpty()){
            return response()->json(['list' => [] ], 200);
        }
        return response()->json(['list' => $plantlist ?? [] ], 200);

    }
}
