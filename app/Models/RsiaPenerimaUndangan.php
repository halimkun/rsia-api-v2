<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;

/**
 * App\Models\RsiaPenerimaUndangan
 *
 * @property string|null $no_surat
 * @property string|null $penerima
 * @property string|null $tipe
 * @property string|null $model
 * @property string $created_at
 * @property-read \App\Models\Pegawai|null $detail
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaPenerimaUndangan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaPenerimaUndangan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaPenerimaUndangan query()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaPenerimaUndangan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaPenerimaUndangan whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaPenerimaUndangan whereNoSurat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaPenerimaUndangan wherePenerima($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaPenerimaUndangan whereTipe($value)
 * @mixin \Eloquent
 */
class RsiaPenerimaUndangan extends Model
{
    use HasCompositeKey, Compoships;

    protected $table = 'rsia_penerima_undangan';

    protected $primaryKey = ['no_surat', 'penerima', 'tipe'];

    public $incrementing = false;

    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'no_surat' => 'string',
        'penerima' => 'string',
        'tipe'     => 'string',
        'model'    => 'string',
    ];


    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'penerima', 'nik')->select('nik', 'nama', 'jbtn', 'departemen', 'bidang');
    }
    
    public function detail()
    {
        return $this->belongsTo(Pegawai::class, 'penerima', 'nik')->select('nik', 'nama', 'jbtn', 'departemen', 'bidang');
    }

    // Relasi Polimorfik
    public function relatedModel(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo('no_surat', 'model', 'no_surat', 'no_surat');
    }

    public function scopeSearchByRelatedModel($query, $searchTerm)
    {
        return $query->whereHas('relatedModel', function ($query) use ($searchTerm) {
            $query->where('perihal', 'like', "%{$searchTerm}%");
        });
    }

    public function scopeWhereBetweenDate($query, $start, $end)
    {
        if ($start == null || $end == null) {
            return $query->whereHas('relatedModel');
        } else {
            return $query->whereHas('relatedModel', function ($query) use ($start, $end) {
                $query->whereBetween('tanggal', [$start, $end]);
            });
        }
    }

    public function scopeWithDetail($query)
    {
        return $query->with(['relatedModel', 'detail']);
    }

    public function kehadiran()
    {
        return $this->hasOne(RsiaKehadiranRapat::class, ['no_surat', 'nik'], ['no_surat', 'penerima'])->without('pegawai');
    }
}
