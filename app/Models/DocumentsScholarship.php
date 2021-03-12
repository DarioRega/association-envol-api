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
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentsScholarship newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentsScholarship newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentsScholarship query()
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentsScholarship whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentsScholarship whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentsScholarship whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentsScholarship whereScholarshipId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentsScholarship whereSrcUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentsScholarship whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Scholarship[] $scholarships
 * @property-read int|null $scholarships_count
 * @property string $mimeType
 * @method static \Illuminate\Database\Eloquent\Builder|DocumentsScholarship whereMimeType($value)
 */
class DocumentsScholarship extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'srcUrl', 'mimeType', 'scholarship_id'];

    public function scholarships()
    {
        return $this->belongsToMany(Scholarship::class);
    }
}
