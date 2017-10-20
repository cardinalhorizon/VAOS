<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TypeRating
 *
 * @property int $id
 * @property int $airline_id
 * @property string $name
 * @property string $identifier
 * @property string $description
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TypeRating whereAirlineId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TypeRating whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TypeRating whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TypeRating whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TypeRating whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TypeRating whereIdentifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TypeRating whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TypeRating whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TypeRating extends Model
{
    protected $fillable = ['airline_id', 'name', 'identifier', 'description'];

    public function airline()
    {
        //
    }
}
