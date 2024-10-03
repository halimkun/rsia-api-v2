<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RanapGabung
 *
 * @property string $no_rawat
 * @property string $no_rawat2
 * @method static \Illuminate\Database\Eloquent\Builder|RanapGabung newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RanapGabung newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RanapGabung query()
 * @method static \Illuminate\Database\Eloquent\Builder|RanapGabung whereNoRawat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RanapGabung whereNoRawat2($value)
 * @mixin \Eloquent
 */
class RanapGabung extends Model
{
    use HasFactory;

    protected $table = 'ranap_gabung';

    protected $primaryKey = 'no_rawat';

    protected $fillable = [
        'no_rawat',
        'no_rawat2'
    ];

    public $timestamps = false;

    public $incrementing = false;
}
