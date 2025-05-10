<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\SeedsDispatch;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SeedsDispatchController extends Controller
{
    public function add(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "plant_type"     => 'required',
            "quantity"       => 'required',
            "sold_to"        => 'required',
            "price_per_unit" => 'required',
            "total_price"    => 'required',
            "receiver_id"    => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json(["message" => $validate->errors()->first()], 400);
        }

        $data = new SeedsDispatch();
        $data->plant_type     = $request->plant_type;
        $data->quantity       = $request->quantity;
        $data->sold_to        = $request->sold_to;
        $data->price_per_unit = $request->price_per_unit;
        $data->total_price    = $request->total_price;
        $data->receiver_id    = $request->receiver_id;
        $data->created_at     = Carbon::now();
        $data->save();
        return response()->json(['message' => trans('messages.ADDED_SUCESSFULLY', ['title' =>  'Seeds Dispatch'])], 200);
    }

    public function edit(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "id" => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json(["message" => $validate->errors()->first()], 400);
        }
        $query  = SeedsDispatch::where('id', $request->id)->first();
        if (!empty($query)) {
            $query->plant_type     = $request->plant_type ?? $query->plant_type;
            $query->quantity       = $request->quantity ?? $query->quantity;
            $query->sold_to        = $request->sold_to ?? $query->sold_to;
            $query->price_per_unit = $request->price_per_unit ?? $query->price_per_unit;
            $query->total_price    = $request->total_price ?? $query->total_price;
            $query->receiver_id    = $request->receiver_id ?? $query->receiver_id;
            $query->updated_at     = Carbon::now();
            $query->save();
            return response()->json(['message' => trans('messages.UPDATE_SUCESSFULLY', ['title' => 'Seeds Dispatch'])], 200);
        } else {
            return response()->json(['message' => trans('messages.SOMETHING_WENT_WRONG')], 400);
        }
    }

    public function details($id)
    {
        $result = SeedsDispatch::where('id', '=', $id)->first();
        if (!empty($result)) {
            return response()->json(['status' => 'success', 'result' => $result], 200);
        } else {
            return response()->json(['status' => 'error', 'message' => trans("messages.NO_RECORD_FOUND")], 400);
        }
    }

    public function updateStatus(Request $request)
    {
        $query = SeedsDispatch::where('id', $request->id)->first();
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
        $list       = SeedsDispatch::list($index, $limit, $request);
        $count      = SeedsDispatch::list(-1, $limit, $request);
        $nextOffset = Helper::getWithLimitNextOffset($offset, count($list), $limit);
        return response()->json(['count' => $count, 'next_offset' => $nextOffset, 'list' => $list ?? []], 200);
    }
}
