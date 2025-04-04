<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Income;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IncomeController extends Controller
{
    public function add(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "amount"  => 'required',
            "receiver_id" => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json(["message" => $validate->errors()->first()], 400);
        }

        $data = new Income();
        $data->amount      = $request->amount;
        $data->receiver_id = $request->receiver_id;
        $data->remarks     = $request->remarks ?? '';
        $data->created_at  = Carbon::now();
        $data->save();
        return response()->json(['message' => trans('messages.ADDED_SUCESSFULLY', ['title' =>  'Income'])], 200);
    }

    public function edit(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "id"      => 'required',
            "amount"  => 'required',
            "receiver_id" => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json(["message" => $validate->errors()->first()], 400);
        }
        $query  = Income::where('id', $request->id)->first();
        if (!empty($query)) {
            $query->amount      = $request->amount ?? $query->amount;
            $query->receiver_id = $request->receiver_id ?? $query->receiver_id;
            $query->remarks     = $request->remarks ?? $query->remarks;
            $query->updated_at  = Carbon::now();
            $query->save();
            return response()->json(['message' => trans('messages.UPDATE_SUCESSFULLY', ['title' => 'Income'])], 200);
        } else {
            return response()->json(['message' => trans('messages.SOMETHING_WENT_WRONG')], 400);
        }
    }

    public function details($id)
    {
        $result = Income::where('id', '=', $id)->first();
        if (!empty($result)) {
            return response()->json(['status' => 'success', 'result' => $result], 200);
        } else {
            return response()->json(['status' => 'error', 'message' => trans("messages.NO_RECORD_FOUND")], 400);
        }
    }

    public function paginationlist(Request $request)
    {
        $offset     = (int) trim($request->input('offset'));
        $limit      = (int) trim($request->input('limit'));
        $index      = Helper::getIndexWithLimit($offset, $limit);
        $list       = Income::list($index, $limit, $request);
        $count      = Income::list(-1, $limit, $request);
        $nextOffset = Helper::getWithLimitNextOffset($offset, count($list), $limit);
        return response()->json(['count' => $count, 'next_offset' => $nextOffset, 'list' => $list ?? []], 200);
    }
}
