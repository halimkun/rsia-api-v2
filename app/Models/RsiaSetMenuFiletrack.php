<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
