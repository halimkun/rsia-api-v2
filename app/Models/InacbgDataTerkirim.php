<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InacbgDataTerkirim extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'inacbg_data_terkirim';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'no_sep';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'no_sep' => 'string',
        'no_rawat' => 'string',
    ];

    /**
     * Get the bridging sep that owns the inacbg data terkirim.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bridgingSep()
    {
        return $this->belongsTo(BridgingSep::class, 'no_sep', 'no_sep');
    }

    /**
     * Get the reg periksa that owns the inacbg data terkirim.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function regPeriksa()
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat')->select('no_rawat', 'tgl_registrasi', 'jam_reg');
    }
}
