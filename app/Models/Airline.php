<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Airline
 *
 * @property int $id
 * @property string $icao
 * @property string $fshub_id
 * @property string|null $iata
 * @property string $name
 * @property string|null $logo
 * @property string|null $widget
 * @property string $callsign
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Hub[] $hubs
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Airline whereCallsign($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Airline whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Airline whereFshubId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Airline whereIata($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Airline whereIcao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Airline whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Airline whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Airline whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Airline whereWidget($value)
 * @mixin \Eloquent
 */
class Airline extends Model
{
    public $timestamps = false;

    protected $fillable = ['icao', 'icao','fshub_id', 'iata', 'name', 'logo', 'widget', 'callsign'];

    public function hubs()
    {
        return $this->hasMany('App\Models\Hub');
    }
}
