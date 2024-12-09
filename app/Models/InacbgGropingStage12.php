<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\InacbgGropingStage12
 *
 * @property string $no_sep
 * @property string|null $code_cbg
 * @property string|null $deskripsi
 * @property float|null $tarif
 * @method static \Illuminate\Database\Eloquent\Builder|InacbgGropingStage12 newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InacbgGropingStage12 newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InacbgGropingStage12 query()
 * @method static \Illuminate\Database\Eloquent\Builder|InacbgGropingStage12 whereCodeCbg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InacbgGropingStage12 whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InacbgGropingStage12 whereNoSep($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InacbgGropingStage12 whereTarif($value)
 * @mixin \Eloquent
 */
class InacbgGropingStage12 extends Model
{
    use HasFactory;

    protected $table = 'inacbg_grouping_stage12';

    protected $guarded = [];

    public $incrementing = false;

    public $timestamps = false;

    
    public function naikKelas()
    {
        return $this->belongsTo(RsiaNaikKelas::class, 'no_sep', 'no_sep');
    }
}
