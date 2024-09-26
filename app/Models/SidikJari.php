<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SidikJari
 *
 * @property string $id
 * @property string $sidikjari
 * @method static \Illuminate\Database\Eloquent\Builder|SidikJari newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SidikJari newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SidikJari query()
 * @method static \Illuminate\Database\Eloquent\Builder|SidikJari whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SidikJari whereSidikjari($value)
 * @mixin \Eloquent
 */
class SidikJari extends Model
{
    use HasFactory;

    protected $table = 'sidikjari';

    protected $primaryKey = 'id';

    public $incrementing = false;

    public $timestamps = false;

    // protected $guarded = [];

    protected $casts = [
        'id' => 'string',
    ];
}
