<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\PlantBooking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlantBookingController extends Controller
{
    public function add(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "farmer_id"   => 'required',
            "plant_type"  => 'required',
            "quantity"  => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json(["message" => $validate->errors()->first()], 400);
        }

        $data = new PlantBooking();
        $data->farmer_id      = $request->farmer_id; //  from user table
        $data->plant_type     = $request->plant_type; //  from plant table
        $data->quantity       = $request->quantity ?? 0;
        $data->booking_date   = $request->booking_date ?? Carbon::now();
        $data->delivery_date  = $request->delivery_date;
        $data->status         = $request->status ?? 'Pending';
        $data->remarks        = $request->remarks ?? '';
        $data->created_at     = Carbon::now();
        $data->save();
        return response()->json(['message' => trans('messages.ADDED_SUCESSFULLY', ['title' =>  'Plant Booking'])], 200);
    }

    public function edit(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "id"   => 'required',
            "farmer_id"   => 'required',
            "plant_type"  => 'required',
            "quantity"  => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json(["message" => $validate->errors()->first()], 400);
        }
        $query  = PlantBooking::where('id', $request->id)->first();
        if (!empty($query)) {
            $query->farmer_id      = $request->farmer_id ?? $query->farmer_id;
            $query->plant_type     = $request->plant_type ?? $query->plant_type;
            $query->quantity       = $request->quantity ?? $query->quantity;
            $query->booking_date   = $request->booking_date ?? $query->booking_date;
            $query->delivery_date  = $request->delivery_date ?? $query->delivery_date;
            $query->status         = $request->status ?? $query->status;
            $query->remarks        = $request->remarks ?? $query->remarks;
            $query->updated_at      = Carbon::now();
            $query->save();
            return response()->json(['message' => trans('messages.UPDATE_SUCESSFULLY', ['title' => 'Plant Booking'])], 200);
        } else {
            return response()->json(['message' => trans('messages.SOMETHING_WENT_WRONG')], 400);
        }
    }

    public function details($id)
    {
        $result = PlantBooking::where('id', '=', $id)->first();
        if (!empty($result)) {
            return response()->json(['status' => 'success', 'result' => $result], 200);
        } else {
            return response()->json(['status' => 'error', 'message' => trans("messages.NO_RECORD_FOUND")], 400);
        }
    }

    public function updateStatus(Request $request)
    {
        $query = PlantBooking::where('id', $request->id)->first();
        if (!empty($query)) {
            $query->status = $request->status;
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
        $list       = PlantBooking::list($index, $limit, $request);
        $count      = PlantBooking::list(-1, $limit, $request);
        $nextOffset = Helper::getWithLimitNextOffset($offset, count($list), $limit);
        return response()->json(['count' => $count, 'next_offset' => $nextOffset, 'list' => $list ?? []], 200);
    }
}
