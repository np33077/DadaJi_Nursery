<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = "users";
    public $timestamps = false;

    use HasApiTokens, HasFactory, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'contact',
        'address',
        'password',
        'created_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public static function isUserValid()
    {
        $user = Auth::user();
        if (empty($user)) {
            return response()->json(['status' => trans('messages.ERROR'), 'message' => trans('messages.UNAUTHORIZED')], 401);
        }
        $user_detail =  User::where('id', $user->id)->first();
        return $user_detail;
    }
    public static function list($index, $limit, $request){
        $keyword  = $request->query('keyword');
        $sort_by  = $request->query('sort_by');
        $order_by = $request->query('order_by');
        $query    = self::select("*")->where('status', 'Y');
        // if (!empty($keyword)) {
        //     $query->where(function ($q) use ($keyword) {
        //         $q->where("plant_name", 'LIKE', "%$keyword%");
        //     });
        // }

        if ($index != -1) {
            if (!empty($sort_by) && !empty($order_by)) {
                $query->orderBy($sort_by, $order_by);
            } else {
                $query->orderBy('created_at', 'desc');
            }
            if (!empty($limit)) {
                $query->limit($limit);
            } else {
                $query->limit(config("constants.LIMIT"));
            }
            return $query->offset($index)
                ->get();
        } else {
            return $query->count();
        }
    }
    public function test($index, $limit, $request)
    {
        echo "helel-p";
        exit;
    }
}
