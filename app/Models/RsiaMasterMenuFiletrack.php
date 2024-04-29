<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
