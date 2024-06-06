<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RsiaSpoDetail
 *
 * @property string $nomor
 * @property string|null $pengertian
 * @property string|null $tujuan
 * @property string|null $kebijakan
 * @property string|null $prosedur
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSpoDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSpoDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSpoDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSpoDetail whereKebijakan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSpoDetail whereNomor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSpoDetail wherePengertian($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSpoDetail whereProsedur($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSpoDetail whereTujuan($value)
 * @mixin \Eloquent
 */
class RsiaSpoDetail extends Model
{
    use HasFactory;

    protected $table = 'rsia_spo_detail';

    protected $guarded = [];

    protected $primaryKey = 'nomor';
    
    public $timestamps = false;

    public $incrementing = false;

    public $casts = [
        'nomor' => 'string',
    ];
}
