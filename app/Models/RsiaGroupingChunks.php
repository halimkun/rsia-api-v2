<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RsiaGroupingChunks extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rsia_grouping_chunks';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'no_sep';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    
    /**
     * Get the bridging_sep that owns the RsiaGroupingChunks.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bridging_sep()
    {
        return $this->belongsTo(BridgingSep::class, 'no_sep', 'no_sep');
    }
}
