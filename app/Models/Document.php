<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Document
 *
 * @property int $id
 * @property string $name
 * @property string $srcUrl
 * @property int $is_external
 * @property int $type_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Type $type
 * @method static \Illuminate\Database\Eloquent\Builder|Document newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Document newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Document query()
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereIsExternal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereSrcUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $year_to_classify
 * @property int $is_archived
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereIsArchived($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereYearToClassify($value)
 */
class Document extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'srcUrl', 'is_external'];
    protected $casts = [
        'is_external' => 'boolean',
    ];

    public function type(){
        return $this->belongsTo(Type::class);
    }


}
