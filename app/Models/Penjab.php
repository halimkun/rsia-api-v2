<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Penjab
 *
 * @property string $kd_pj
 * @property string $png_jawab
 * @property string $nama_perusahaan
 * @property string $alamat_asuransi
 * @property string $no_telp
 * @property string $attn
 * @property string $status
 * @method static \Illuminate\Database\Eloquent\Builder|Penjab newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Penjab newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Penjab query()
 * @method static \Illuminate\Database\Eloquent\Builder|Penjab whereAlamatAsuransi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Penjab whereAttn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Penjab whereKdPj($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Penjab whereNamaPerusahaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Penjab whereNoTelp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Penjab wherePngJawab($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Penjab whereStatus($value)
 * @mixin \Eloquent
 */
class Penjab extends Model
{
    use HasFactory;

    protected $table = 'penjab';

    protected $primaryKey = 'kd_pj';

    protected $guarded = [];

    public $primaryKeyType = 'string';

    public $incrementing = false;
}
