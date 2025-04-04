<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Expenses;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends Controller
{
    public function add(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "type"    => 'required',
            "amount"  => 'required',
            "user_id" => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json(["message" => $validate->errors()->first()], 400);
        }

        $data = new Expenses();
        $data->type           = $request->type;
        $data->amount         = $request->amount;
        $data->user_id        = $request->user_id;
        $data->remarks        = $request->remarks ?? '';
        $data->created_at     = Carbon::now();
        $data->save();
        return response()->json(['message' => trans('messages.ADDED_SUCESSFULLY', ['title' =>  'Expenses'])], 200);
    }

    public function edit(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "id"      => 'required',
            "type"    => 'required',
            "amount"  => 'required',
            "user_id" => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json(["message" => $validate->errors()->first()], 400);
        }
        $query  = Expenses::where('id', $request->id)->first();
        if (!empty($query)) {
            $query->type           = $request->type ?? $query->type;
            $query->amount         = $request->amount ?? $query->amount;
            $query->user_id        = $request->user_id ?? $query->user_id;
            $query->remarks        = $request->remarks ?? $query->remarks;
            $query->updated_at      = Carbon::now();
            $query->save();
            return response()->json(['message' => trans('messages.UPDATE_SUCESSFULLY', ['title' => 'Expenses'])], 200);
        } else {
            return response()->json(['message' => trans('messages.SOMETHING_WENT_WRONG')], 400);
        }
    }

    public function details($id)
    {
        $result = Expenses::where('id', '=', $id)->first();
        if (!empty($result)) {
            return response()->json(['status' => 'success', 'result' => $result], 200);
        } else {
            return response()->json(['status' => 'error', 'message' => trans("messages.NO_RECORD_FOUND")], 400);
        }
    }

    // public function updateStatus(Request $request)
    // {
    //     $query = Expenses::where('id', $request->id)->first();
    //     if (!empty($query)) {
    //         $query->status = $request->status;
    //         $query->save();
    //         return response()->json(['message' => trans('messages.STATUS_UPDATED')], 200);
    //     } else {
    //         return response()->json(['status' => 'error', 'message' => trans("messages.NO_RECORD_FOUND")], 400);
    //     }
    // }

    public function paginationlist(Request $request)
    {
        $offset     = (int) trim($request->input('offset'));
        $limit      = (int) trim($request->input('limit'));
        $index      = Helper::getIndexWithLimit($offset, $limit);
        $list       = Expenses::list($index, $limit, $request);
        $count      = Expenses::list(-1, $limit, $request);
        $nextOffset = Helper::getWithLimitNextOffset($offset, count($list), $limit);
        return response()->json(['count' => $count, 'next_offset' => $nextOffset, 'list' => $list ?? []], 200);
    }
}
