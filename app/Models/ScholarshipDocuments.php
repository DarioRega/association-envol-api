<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ScholarshipDocuments
 *
 * @property int $id
 * @property string $name
 * @property string $srcUrl
 * @property int|null $scholarship_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Scholarship[] $scholarship
 * @property-read int|null $scholarship_count
 * @method static \Illuminate\Database\Eloquent\Builder|ScholarshipDocuments newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ScholarshipDocuments newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ScholarshipDocuments query()
 * @method static \Illuminate\Database\Eloquent\Builder|ScholarshipDocuments whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScholarshipDocuments whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScholarshipDocuments whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScholarshipDocuments whereScholarshipId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScholarshipDocuments whereSrcUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScholarshipDocuments whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ScholarshipDocuments extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'srcUrl'];

    public function scholarship()
    {
        return $this->belongsToMany(Scholarship::class);
    }
}
