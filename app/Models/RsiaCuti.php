<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RsiaCuti extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * 
     * @var string
     * 
     */
    protected $table = 'rsia_cuti';

    /**
     * The primary key associated with the table.
     * 
     * @var string
     * */ 
    protected $primaryKey = 'id_cuti';

    /**
     * The primary key associated with the table.
     * 
     * @var string
     * 
     */
    protected $guarded = ['id_cuti'];

    /**
     * Indicates if the model should be timestamped.
     * 
     * @var bool 
    */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id_pegawai');
    }

    /**
     * Get the pegawai that owns the RsiaCuti
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pegawaiNik()
    {
        return $this->belongsTo(Pegawai::class, 'nik', 'nik');
    }

    /**
     * Get the departemen that owns the RsiaCuti
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function departemen()
    {
        return $this->belongsTo(Departemen::class, 'dep_id', 'dep_id');
    }
}
