<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MLiteVedikaFeeedback
 * 
 * @property int $id
 * @property string $nosep
 * @property \Illuminate\Support\Carbon $tanggal
 * @property string $catatan
 * @property string $username
 * 
 * @property-read \App\Models\BridgingSep $sep
 * @property-read \App\Models\Pegawai $user
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|MLiteVedikaFeeedback newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MLiteVedikaFeeedback newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MLiteVedikaFeeedback query()
 * @method static \Illuminate\Database\Eloquent\Builder|MLiteVedikaFeeedback whereCatatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MLiteVedikaFeeedback whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MLiteVedikaFeeedback whereNosep($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MLiteVedikaFeeedback whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MLiteVedikaFeeedback whereUsername($value)
 * 
 * @mixin \Eloquent
 */
class MLiteVedikaFeeedback extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mlite_vedika_feedback';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ["nosep", "tanggal", "catatan", "username", "created_at", "updated_at"];

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    public $casts = [
        'tanggal' => 'date'
    ];

    /**
     * Get the sep that owns the MLiteVedikaFeeedback
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sep()
    {
        return $this->belongsTo(BridgingSep::class, 'nosep', 'no_sep');
    }

    /**
     * Get the user that owns the MLiteVedikaFeeedback
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(Pegawai::class, 'username', 'nik');
    }
}
