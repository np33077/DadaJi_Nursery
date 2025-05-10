<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Transactions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionsController extends Controller
{
    public function add(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "type"         => 'required',
            "amount"       => 'required',
            "reference_id" => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json(["message" => $validate->errors()->first()], 400);
        }

        $data = new Transactions();
        $data->type            = $request->type;
        $data->amount          = $request->amount;
        $data->reference_id    = $request->reference_id;
        $data->reference_type  = $request->reference_type ?? "";
        $data->remarks         = $request->remarks ?? "";
        $data->created_at      = Carbon::now();
        $data->save();
        return response()->json(['message' => trans('messages.ADDED_SUCESSFULLY', ['title' =>  'Transactions'])], 200);
    }

    public function edit(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "id" => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json(["message" => $validate->errors()->first()], 400);
        }
        $query  = Transactions::where('id', $request->id)->first();
        if (!empty($query)) {
            $query->type           = $request->type ?? $query->type;
            $query->amount         = $request->amount ?? $query->amount;
            $query->reference_id   = $request->reference_id ?? $query->reference_id;
            $query->reference_type = $request->reference_type ?? $query->reference_type;
            $query->remarks        = $request->remarks ?? $query->remarks;
            $query->updated_at     = Carbon::now();
            $query->save();
            return response()->json(['message' => trans('messages.UPDATE_SUCESSFULLY', ['title' => 'Transactions'])], 200);
        } else {
            return response()->json(['message' => trans('messages.SOMETHING_WENT_WRONG')], 400);
        }
    }

    public function details($id)
    {
        $result = Transactions::where('id', '=', $id)->first();
        if (!empty($result)) {
            return response()->json(['status' => 'success', 'result' => $result], 200);
        } else {
            return response()->json(['status' => 'error', 'message' => trans("messages.NO_RECORD_FOUND")], 400);
        }
    }

    public function updateStatus(Request $request)
    {
        $query = Transactions::where('id', $request->id)->first();
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
        $list       = Transactions::list($index, $limit, $request);
        $count      = Transactions::list(-1, $limit, $request);
        $nextOffset = Helper::getWithLimitNextOffset($offset, count($list), $limit);
        return response()->json(['count' => $count, 'next_offset' => $nextOffset, 'list' => $list ?? []], 200);
    }
}
