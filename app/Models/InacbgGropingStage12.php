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

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'inacbg_grouping_stage12';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $guarded = [];

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'no_sep';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    public $incrementing = false;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;


    public function scopeGetGrade3($query)
    {
        return $query->where('code_cbg', 'like', '%III');
    }


    /**
     * Get the naikKelas that owns the InacbgGropingStage12
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function naikKelas()
    {
        return $this->belongsTo(RsiaNaikKelas::class, 'no_sep', 'no_sep');
    }

    /**
     * Get the sep that owns the InacbgGropingStage12
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sep()
    {
        return $this->belongsTo(BridgingSep::class, 'no_sep', 'no_sep')
            ->select('no_sep', 'no_rawat', 'tglsep', 'jnspelayanan', 'diagawal', 'nmdiagnosaawal', 'klsrawat', 'klsnaik', 'nomr', 'no_kartu');
    }

    public function kamarInap()
    {
        return $this->hasMany(KamarInap::class, 'no_rawat', 'no_rawat');
    }
}
