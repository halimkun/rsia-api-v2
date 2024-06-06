<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Spesialis
 *
 * @property string $kd_sps
 * @property string|null $nm_sps
 * @method static \Illuminate\Database\Eloquent\Builder|Spesialis newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Spesialis newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Spesialis query()
 * @method static \Illuminate\Database\Eloquent\Builder|Spesialis whereKdSps($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Spesialis whereNmSps($value)
 * @mixin \Eloquent
 */
class Spesialis extends Model
{
    use HasFactory;

    protected $table = 'spesialis';

    protected $primaryKey = 'kd_sps';

    protected $keyType = 'string';
        
    // protected $guarded = [];

    public $incrementing = false;

    public $timestamps = false;
}
