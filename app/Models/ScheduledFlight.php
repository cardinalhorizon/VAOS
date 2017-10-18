<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ScheduledFlight
 *
 * @property int $id
 * @property int $airline_id
 * @property string $flightnum
 * @property string|null $sub
 * @property int $depapt_id
 * @property int $arrapt_id
 * @property int $seasonal
 * @property string|null $startdate
 * @property string|null $enddate
 * @property string|null $deptime
 * @property string|null $arrtime
 * @property int $type
 * @property int $enabled
 * @property string|null $notes
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ScheduledFlight whereAirlineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ScheduledFlight whereArraptId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ScheduledFlight whereArrtime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ScheduledFlight whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ScheduledFlight whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ScheduledFlight whereDepaptId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ScheduledFlight whereDeptime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ScheduledFlight whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ScheduledFlight whereEnddate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ScheduledFlight whereFlightnum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ScheduledFlight whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ScheduledFlight whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ScheduledFlight whereSeasonal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ScheduledFlight whereStartdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ScheduledFlight whereSub($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ScheduledFlight whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ScheduledFlight whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ScheduledFlight extends Model
{
    //
}
