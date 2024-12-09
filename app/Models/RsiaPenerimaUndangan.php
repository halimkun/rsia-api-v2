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
 * @property string $updated_at
 * @property-read Model|\Eloquent $relatedModel
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaPenerimaUndangan searchByRelatedModel($searchTerm)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaPenerimaUndangan whereBetweenDate($start, $end)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaPenerimaUndangan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaPenerimaUndangan withDetail()
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

    /**
     * Get the detail that owns the RsiaPenerimaUndangan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function detail()
    {
        return $this->belongsTo(Pegawai::class, 'penerima', 'nik')->select('nik', 'nama', 'jbtn', 'departemen', 'bidang');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'penerima', 'nik')->select('nik', 'nama', 'jbtn', 'departemen', 'bidang');
    }

    public function petugas()
    {
        return $this->belongsTo(Petugas::class,  'penerima', 'nip');
    }

    /**
     * Get the related model that owns the RsiaPenerimaUndangan
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function relatedModel(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo('no_surat', 'model', 'no_surat', 'no_surat');
    }

    /**
     * Scope a query to search by related model.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $searchTerm
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearchByRelatedModel($query, $searchTerm)
    {
        return $query->whereHas('relatedModel', function ($query) use ($searchTerm) {
            $query->where('perihal', 'like', "%{$searchTerm}%");
        });
    }

    /**
     * Scope a query to search by related model.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $start
     * @param string $end
     * @return \Illuminate\Database\Eloquent\Builder
     */
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

    /**
     * Scope a query to eager load related model and detail.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithDetail($query)
    {
        return $query->with(['relatedModel', 'detail']);
    }

    public function kehadiran()
    {
        return $this->hasOne(RsiaKehadiranRapat::class, ['no_surat', 'nik'], ['no_surat', 'penerima'])->without('pegawai');
    }
}
