<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PaypalPlan
 *
 * @property int $id
 * @property string $plan_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PaypalPlan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaypalPlan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaypalPlan query()
 * @method static \Illuminate\Database\Eloquent\Builder|PaypalPlan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaypalPlan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaypalPlan whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaypalPlan wherePlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaypalPlan whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PaypalPlan extends Model
{
    use HasFactory;
    protected $fillable = ['plan_id', 'name'];
}
