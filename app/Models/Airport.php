<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Airport
 *
 * @property int $id
 * @property string $name
 * @property string $city
 * @property string $country
 * @property string $iata
 * @property string $icao
 * @property float $lat
 * @property float $lon
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ScheduledFlight[] $scheduledflight
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Airport whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Airport whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Airport whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Airport whereIata($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Airport whereIcao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Airport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Airport whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Airport whereLon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Airport whereName($value)
 * @mixin \Eloquent
 */
class Airport extends Model
{
    public $timestamps = false;

    protected $fillable = ['id', 'name', 'city', 'county', 'iata','icao', 'lat', 'lon'];

    public function scheduledflight()
    {
        return $this->hasMany('App\Models\ScheduledFlight');
    }

    public function bid()
    {
        return $this->hasMany('App\Models\Bid');
    }
}
