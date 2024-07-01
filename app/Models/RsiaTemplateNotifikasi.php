<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RsiaTemplateNotifikasi extends Model
{
    use HasFactory;

    protected $table = 'rsia_template_notifikasi';

    protected $guarded = ['id'];

    public $timestamps = false;
}
