<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\RsiaMasterMenuFiletrack
 *
 * @property int $id
 * @property string|null $group
 * @property string $label
 * @property string $icon
 * @property string $url
 * @property int $urutan
 * @property int $status
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RsiaSetMenuFiletrack> $setMenu
 * @property-read int|null $set_menu_count
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaMasterMenuFiletrack newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaMasterMenuFiletrack newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaMasterMenuFiletrack query()
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaMasterMenuFiletrack whereGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaMasterMenuFiletrack whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaMasterMenuFiletrack whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaMasterMenuFiletrack whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaMasterMenuFiletrack whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaMasterMenuFiletrack whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RsiaMasterMenuFiletrack whereUrutan($value)
 * @mixin \Eloquent
 */
class RsiaMasterMenuFiletrack extends Model
{
    use HasFactory;

    protected $table = 'rsia_master_menu_filetrack';

    protected $guarded = ['id'];

    public function setMenu()
    {
        return $this->hasMany(RsiaSetMenuFiletrack::class, 'menu_id', 'id');
    }
}
