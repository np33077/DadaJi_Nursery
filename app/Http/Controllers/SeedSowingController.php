<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\SeedSowing;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SeedSowingController extends Controller
{
    public function add(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "plant_id"              => 'required',
            "sowing_date"           => 'required',
            "expected_harvest_date" => 'required',
            "quantity"              => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json(["message" => $validate->errors()->first()], 400);
        }

        $data = new SeedSowing();
        $data->plant_id              = $request->plant_id;
        $data->sowing_date           = $request->sowing_date;
        $data->expected_harvest_date = $request->expected_harvest_date;
        $data->quantity              = $request->quantity;
        $data->remarks               = $request->remarks ?? "";
        $data->created_at            = Carbon::now();
        $data->save();
        return response()->json(['message' => trans('messages.ADDED_SUCESSFULLY', ['title' =>  'Seed Sowing'])], 200);
    }

    public function edit(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "id" => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json(["message" => $validate->errors()->first()], 400);
        }
        $query  = SeedSowing::where('id', $request->id)->first();
        if (!empty($query)) {
            $query->plant_id              = $request->plant_id ?? $query->plant_id;
            $query->sowing_date           = $request->sowing_date ?? $query->sowing_date;
            $query->expected_harvest_date = $request->expected_harvest_date ?? $query->expected_harvest_date;
            $query->quantity              = $request->quantity ?? $query->quantity;
            $query->remarks               = $request->remarks ?? $query->remarks;
            $query->updated_at      = Carbon::now();
            $query->save();
            return response()->json(['message' => trans('messages.UPDATE_SUCESSFULLY', ['title' => 'Seed Sowing'])], 200);
        } else {
            return response()->json(['message' => trans('messages.SOMETHING_WENT_WRONG')], 400);
        }
    }

    public function details($id)
    {
        $result = SeedSowing::where('id', '=', $id)->first();
        if (!empty($result)) {
            return response()->json(['status' => 'success', 'result' => $result], 200);
        } else {
            return response()->json(['status' => 'error', 'message' => trans("messages.NO_RECORD_FOUND")], 400);
        }
    }

    public function updateStatus(Request $request)
    {
        $query = SeedSowing::where('id', $request->id)->first();
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
        $list       = SeedSowing::list($index, $limit, $request);
        $count      = SeedSowing::list(-1, $limit, $request);
        $nextOffset = Helper::getWithLimitNextOffset($offset, count($list), $limit);
        return response()->json(['count' => $count, 'next_offset' => $nextOffset, 'list' => $list ?? []], 200);
    }
}
