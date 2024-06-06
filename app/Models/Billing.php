<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Billing
 *
 * @property int $noindex
 * @property string $no_rawat
 * @property string|null $tgl_byr
 * @property string $no
 * @property string $nm_perawatan
 * @property string $pemisah
 * @property float $biaya
 * @property float $jumlah
 * @property float $tambahan
 * @property float $totalbiaya
 * @property string|null $status
 * @method static \Illuminate\Database\Eloquent\Builder|Billing newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Billing newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Billing query()
 * @method static \Illuminate\Database\Eloquent\Builder|Billing whereBiaya($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Billing whereJumlah($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Billing whereNmPerawatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Billing whereNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Billing whereNoRawat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Billing whereNoindex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Billing wherePemisah($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Billing whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Billing whereTambahan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Billing whereTglByr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Billing whereTotalbiaya($value)
 * @mixin \Eloquent
 */
class Billing extends Model
{
    use HasFactory;

    protected $table = 'billing';

    protected $primaryKey = '';

    protected $guarded = [];

    protected $casts = [
        'no_rawat' => 'string'
    ];
    
    public $timestamps = false;

    public $incrementing = false;
}
