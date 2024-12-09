<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\User
 * 
 * @property string $no_sep
 * @property string $no_rawat
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read \App\Models\BridgingSep $sep
 * @property-read \App\Models\RegPeriksa $rawat
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaStatusKlaim newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaStatusKlaim newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaStatusKlaim query()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaStatusKlaim whereNoRawat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaStatusKlaim whereNoSep($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaStatusKlaim whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaStatusKlaim whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaStatusKlaim whereUpdatedAt($value)
 * 
 * @mixin \Eloquent
 */
class RsiaStatusKlaim extends Model
{
    use HasFactory;

    /**
     * The table associated with the model
     *
     * @var string
     */
    protected $table = 'rsia_status_klaim';

    /**
     * The primary key associated with the table
     *
     * @var string
     */
    public $incrementing = false;

    /**
     * The primary key associated with the table
     *
     * @var string
     */
    protected $primaryKey = 'no_sep';

    /**
     * The primary key associated with the table
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'no_sep',
        'no_rawat',
        'status',
        'created_at',
        'updated_at'
    ];

    /**
     * Get the sep that owns the RsiaStatusKlaim
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sep()
    {
        return $this->belongsTo(BridgingSep::class, 'no_sep', 'no_sep');
    }

    /**
     * Get the rawat that owns the RsiaStatusKlaim
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rawat()
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }

    /**
     * Get the logs for the RsiaStatusKlaim
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logs()
    {
        return $this->hasMany(MLiteVedika::class, 'no_sep', 'no_sep');
    }
}
