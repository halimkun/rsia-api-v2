<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RsiaVerifSep extends Model
{
    use HasFactory;

    protected $table = 'rsia_verif_sep';

    protected $primaryKey = 'no_sep';

    protected $guarded = [];

    public $timestamps = false;

    protected $keyType = 'string';
}
