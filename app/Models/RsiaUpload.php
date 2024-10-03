<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RsiaUpload extends Model
{
    use HasFactory;

    protected $table = 'rsia_upload';

    protected $primaryKey = 'id';

    protected $guarded = [];

    public $timestamps = false;
}
