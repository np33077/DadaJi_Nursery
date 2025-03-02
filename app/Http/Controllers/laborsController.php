<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Labor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class laborsController extends Controller
{
    public function add(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "name"     => 'required',
            "contact"  => 'required',
            "job_type" => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json(["message" => $validate->errors()->first()], 400);
        }

        $data = new Labor();
        $data->name       = $request->name;
        $data->contact    = $request->contact;
        $data->job_type   = $request->job_type;
        $data->salary     = $request->salary ?? 0;
        $data->hired_date = $request->hired_date ? Carbon::parse($request->hired_date) : '';
        $data->created_at = Carbon::now();
        $data->save();
        return response()->json(['message' => trans('messages.ADDED_SUCESSFULLY', ['title' =>  'Labor'])], 200);
    }

    public function edit(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "id" => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json(["message" => $validate->errors()->first()], 400);
        }
        $query  = Labor::where('id', $request->id)->first();
        if (!empty($query)) {
            $query->name       = $request->name ?? $query->name;
            $query->contact    = $request->contact ?? $query->contact;
            $query->job_type   = $request->job_type ?? $query->job_type;
            $query->salary     = $request->salary ?? $query->salary;
            // $query->hired_date = $request->hired_date ?? $query->hired_date;
            $query->hired_date = Carbon::parse($request->hired_date) ? $query->hired_date : '';
            $query->updated_at = Carbon::now();
            $query->save();
            return response()->json(['message' => trans('messages.UPDATE_SUCESSFULLY', ['title' => 'Labor'])], 200);
        } else {
            return response()->json(['message' => trans('messages.SOMETHING_WENT_WRONG')], 400);
        }
    }

    public function details($id)
    {
        $result = Labor::where('id', '=', $id)->first();
        if (!empty($result)) {
            return response()->json(['status' => 'success', 'result' => $result], 200);
        } else {
            return response()->json(['status' => 'error', 'message' => trans("messages.NO_RECORD_FOUND")], 400);
        }
    }

    public function updateStatus(Request $request)
    {
        $query = Labor::where('id', $request->id)->first();
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
        $list       = Labor::list($index, $limit, $request);
        $count      = Labor::list(-1, $limit, $request);
        $nextOffset = Helper::getWithLimitNextOffset($offset, count($list), $limit);
        return response()->json(['count' => $count, 'next_offset' => $nextOffset, 'list' => $list ?? []], 200);
    }
}
