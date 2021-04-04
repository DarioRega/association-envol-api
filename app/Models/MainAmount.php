<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Interval
 *
 * @property int $id
 * @property int $amount
 * @method static \Illuminate\Database\Eloquent\Builder|MainAmount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MainAmount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MainAmount query()
 * @method static \Illuminate\Database\Eloquent\Builder|MainAmount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MainAmount whereAmount($value)
 * @mixin \Eloquent
 */
class MainAmount extends Model
{
    use HasFactory;
    protected $fillable = ['amount'];

}
