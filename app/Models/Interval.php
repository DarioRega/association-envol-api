<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Interval
 *
 * @property int $id
 * @property string $name
 * @property string $ref
 * @method static \Illuminate\Database\Eloquent\Builder|Interval newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Interval newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Interval query()
 * @method static \Illuminate\Database\Eloquent\Builder|Interval whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Interval whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Interval whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Interval whereRef($value)
 * @mixin \Eloquent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Interval whereUpdatedAt($value)
 */
class Interval extends Model
{
    use HasFactory;
    protected $fillable = ['ref','name'];

}
