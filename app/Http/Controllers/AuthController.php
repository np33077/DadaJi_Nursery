<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // User Registration
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6'
        ]);

        User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => md5($request->password),
            'role'       => $request->role ?? 'Farmer',
            'created_at' => Carbon::now()
        ]);

        return response()->json(['message' => 'User registered successfully'], 201);
    }

    // User Login
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email|exists:users,email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        $user = User::where('email', strtolower($request->email))->first();
        if (!empty($user)) {
            if ($user->password != md5($request->password)) {
                return response()->json(['status' => 'error', 'message' => trans("messages.INVALID_USER_NAME_PASSWORD")], 400);
            } else {
                $objToken = $user->createToken('dadaji_nursery');
                $token = $objToken->accessToken;
                return response()->json(['access_token' => $token, 'status' => 'SUCCESS', 'message' => trans("messages.LOGIN_SUCCESS")], 200);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => trans("messages.SOMETHING_WENT_WRONG")], 400);
        }
    }

    // User Logout
    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function me(Request $request)
    {
        $data = User::isUserValid();
        if (!empty($data)) {
            $user = User::where('id', $data->id)->first();
            return response()->json(['status' => 'success', 'message' => 'ok', 'result' => $user], 200);
        } else {
            return response()->json(['message' => trans('messages.NO_RECORD_FOUND'), 'status' => trans('messages.ERROR')], 400);
        }
    }

    public function farmerList()
    {
        $farmerList = User::where('role', 'Farmer')->get();
        if (!empty($farmerList)) {
            return response()->json(['status' => 'success', 'message' => 'ok', 'result' => $farmerList], 200);
        } else {
            return response()->json(['message' => trans('messages.NO_RECORD_FOUND'), 'status' => trans('messages.ERROR')], 400);
        }
    }

    public function addFarmer(Request $request)
    {  
        $request->validate([
            'name'     => 'required|string|max:255',
        ]);
        $email = 'farmer@yopmail.com';
        $password = md5(123456);

        $farmer =[
            'name'       => $request->name,
            'email'      => $email,
            'contact'    => $request->name,
            'address'    => $request->name,
            'password'   => $password,
            'role'       => $request->role ?? 'Farmer',
            'created_at' => Carbon::now()
        ];
        User::create($farmer);
        return response()->json(['message' => 'User registered successfully'], 201);
    }

    public function farmerListPagination(Request $request)
    {
        $offset     = (int) trim($request->input('offset'));
        $limit      = (int) trim($request->input('limit'));
        $index      = Helper::getIndexWithLimit($offset, $limit);
        $list       = User::list($index, $limit, $request);
        $count      = User::list(-1, $limit, $request);
        $nextOffset = Helper::getWithLimitNextOffset($offset, count($list), $limit);
        return response()->json(['count' => $count, 'next_offset' => $nextOffset, 'list' => $list ?? []], 200);
    }
}
