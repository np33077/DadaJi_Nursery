<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeedSowing extends Model
{
    use HasFactory;
    protected $table = "seed_sowing";
    public $timestamps = true;

    public static function list($index, $limit, $request)
    {
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
}
