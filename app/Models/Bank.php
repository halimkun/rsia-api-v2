<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Bank
 *
 * @property string $namabank
 * @method static \Illuminate\Database\Eloquent\Builder|Bank newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bank newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bank query()
 * @method static \Illuminate\Database\Eloquent\Builder|Bank whereNamabank($value)
 * @mixin \Eloquent
 */
class Bank extends Model
{
    use HasFactory;

    protected $table = "bank";

    protected $primaryKey = "namabank";

    protected $guarded = [];

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;
}
