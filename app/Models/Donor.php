<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Donor
 *
 * @property int $id
 * @property string $customer_id
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Donor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Donor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Donor query()
 * @method static \Illuminate\Database\Eloquent\Builder|Donor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Donor whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Donor whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Donor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Donor whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Donor extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'email'];

}
