<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RsiaSpo
 *
 * @property string $nomor
 * @property string $judul
 * @property string $unit
 * @property string $unit_terkait
 * @property string $tgl_terbit
 * @property string $jenis
 * @property bool $status
 * @property-read \App\Models\RsiaSpoDetail|null $detail
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSpo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSpo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSpo query()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSpo whereJenis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSpo whereJudul($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSpo whereNomor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSpo whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSpo whereTglTerbit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSpo whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSpo whereUnitTerkait($value)
 * @mixin \Eloquent
 */
class RsiaSpo extends Model
{
    use HasFactory;

    protected $table = 'rsia_spo';

    protected $guarded = [];

    protected $primaryKey = 'nomor';
    
    public $timestamps = false;

    public $incrementing = false;

    public $casts = [
        'nomor' => 'string',
        'status' => 'boolean',
    ];

    
    public function detail()
    {
        return $this->hasOne(RsiaSpoDetail::class, 'nomor', 'nomor');
    }
}
