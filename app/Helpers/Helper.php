<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

class Helper
{

    // FUNCTION TO GET INDEX WITH LIMIT FOR PAGINATION

    public static function getIndexWithLimit($offset, $limit = null)
    {
        if (!empty($limit)) {
            if ($offset === 0) {
                return 0;
            } else {
                return ($offset * $limit);
            }
        } else {
            if ($offset === 0) {
                return 0;
            } else {
                return ($offset * config("constants.LIMIT"));
            }
        }
    }

    // FUNCTION TO GET NEXT OFFSET FOR PAGINATION
    
    public static function getWithLimitNextOffset($offset, $count, $limit = null)
    {

        if (!empty($limit)) {
            if ($count == $limit) {
                return ($offset + 1);
            } else {
                return -1;
            }
        } else {

            if ($count === config("constants.LIMIT")) {
                return ($offset + 1);
            } else {
                return -1;
            }
        }
    }


}
