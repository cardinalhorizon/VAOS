<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AircraftGroup
 *
 * @property int $id
 * @property int $airline_id
 * @property string $name
 * @property string|null $icao
 * @property int $userdefined
 * @property string $description
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AircraftGroup whereAirlineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AircraftGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AircraftGroup whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AircraftGroup whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AircraftGroup whereIcao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AircraftGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AircraftGroup whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AircraftGroup whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\AircraftGroup whereUserdefined($value)
 * @mixin \Eloquent
 */
class AircraftGroup extends Model
{
    //
}
