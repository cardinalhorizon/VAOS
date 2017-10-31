<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Hub.
 *
 * @property int $id
 * @property int $airport_id
 * @property int $airline_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Models\Airline $airline
 * @property-read \App\Models\Airport $airport
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Hub whereAirlineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Hub whereAirportId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Hub whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Hub whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Hub whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Hub whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Hub extends Model
{
    protected $fillable = ['airport_id', 'airline_id'];

    public function airport()
    {
        return $this->hasOne('App\Models\Airport');
    }

    public function airline()
    {
        return $this->belongsTo('App\Models\Airline');
    }

    public function user()
    {
        return $this->hasMany('App\Models\User');
    }
}
