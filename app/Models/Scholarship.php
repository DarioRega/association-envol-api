<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Scholarship
 *
 * @property int $id
 * @property string $gender
 * @property string $fullName
 * @property string $email
 * @property string $birthdate
 * @property string $remarks
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Document[] $documents
 * @property-read int|null $documents_count
 * @method static \Illuminate\Database\Eloquent\Builder|Scholarship newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Scholarship newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Scholarship query()
 * @method static \Illuminate\Database\Eloquent\Builder|Scholarship whereBirthdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Scholarship whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Scholarship whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Scholarship whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Scholarship whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Scholarship whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Scholarship whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Scholarship whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $message
 * @method static \Illuminate\Database\Eloquent\Builder|Scholarship whereMessage($value)
 */
class Scholarship extends Model
{
    use HasFactory;
    protected $fillable = ['sex', 'fullName', 'email', 'remarks','birthdate','gender'];

    public function documents()
    {
        return $this->hasMany(DocumentsScholarship::class);
    }
}
