<?php
/**
 * Created by PhpStorm.
 * User: taylorbroad
 * Date: 3/5/18
 * Time: 1:57 PM.
 */

namespace App\Classes;

use App\Models\Airline;
use App\Models\TypeRating;

class VAOS_TypeRatings
{
    public static function AddTypeRating($data)
    {
        $tr       = new TypeRating();
        $airline  = Airline::find($data['airline']);
        $tr->code = $data['code'];
        $tr->name = $data['name'];
        $tr->airline()->associate($airline);
        $tr->save();
    }

    public static function ModifyTypeRating($rating_id, $data)
    {
        //
    }

    public static function AddUserToTypeRating($rating_id, $user_id)
    {
        //
    }

    public static function VerifyRating($rating_id, $user_id)
    {
        //
    }
}
