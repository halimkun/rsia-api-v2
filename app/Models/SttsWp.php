<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SttsWp
 *
 * @property string $stts
 * @property string $ktg
 * @method static \Illuminate\Database\Eloquent\Builder|SttsWp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SttsWp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SttsWp query()
 * @method static \Illuminate\Database\Eloquent\Builder|SttsWp whereKtg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SttsWp whereStts($value)
 * @mixin \Eloquent
 */
class SttsWp extends Model
{
    use HasFactory;

    protected $table = "stts_wp";

    protected $primaryKey = "stts";

    protected $guarded = [];

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;
}
