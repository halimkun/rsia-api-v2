<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RsiaCutiBersalin extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'rsia_cuti_bersalin';

    /**
     * Indicates if the model should be timestamped.
     * 
     * @var bool 
     */
    public $timestamps = false;

    /**
     * The primary key associated with the table.
     * 
     * @var string
     */
    protected $guarded = [];

    /**
     * Get the cuti that owns the RsiaCutiBersalin
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cuti()
    {
        return $this->belongsTo(RsiaCuti::class, 'id_cuti');
    }
}
