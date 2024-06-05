<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateLaboratorium extends Model
{
    use HasFactory;

    protected $table = 'template_laboratorium';

    protected $primaryKey = 'id_template';

    protected $guarded = ['id_template'];

    protected $casts = [
        'no_rawat' => 'string',
    ];

    public $timestamps = false;


    public function detailPeriksaLab()
    {
        return $this->hasMany(DetailPeriksaLab::class, 'id_template', 'id_template');
    }
}
