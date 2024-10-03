<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RsiaNotulen extends Model
{
    use HasFactory;

    /**
     * The table associated with the model
     *
     * @var string
     */
    protected $table = 'rsia_notulen';

    /**
     * The primary key associated with the table
     *
     * @var string
     */
    protected $primaryKey = "no_surat";

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
    public $timestamps = false;

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that are casted
     *
     * @var array
     */
    protected $casts = [
        'no_surat' => 'string',
        'notulis' => 'string',
        'tipe' => 'string',
        'model' => 'string',
    ];


    /**
     * Get the notulis that owns the RsiaNotulen
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function notulis()
    {
        return $this->belongsTo(Pegawai::class, 'notulis_nik', 'nik')->select('nik', 'nama', 'jbtn', 'bidang');
    }
}
