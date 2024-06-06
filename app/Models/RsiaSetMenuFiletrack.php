<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RsiaSetMenuFiletrack
 *
 * @property int $id
 * @property string $nik
 * @property int $menu_id
 * @property-read \App\Models\RsiaMasterMenuFiletrack $menu
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSetMenuFiletrack newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSetMenuFiletrack newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSetMenuFiletrack query()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSetMenuFiletrack whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSetMenuFiletrack whereMenuId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaSetMenuFiletrack whereNik($value)
 * @mixin \Eloquent
 */
class RsiaSetMenuFiletrack extends Model
{
    use HasFactory;

    protected $table = 'rsia_set_menu_filetrack';

    protected $guarded = ['id'];

    protected $with = ['menu'];

    public function menu()
    {
        return $this->belongsTo(RsiaMasterMenuFiletrack::class, 'menu_id', 'id');
    }
}
