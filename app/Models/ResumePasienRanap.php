<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResumePasienRanap extends Model
{
    use HasFactory;

    protected $table = 'resume_pasien_ranap';

    protected $primaryKey = 'no_rawat';

    protected $guarded = [];

    public $timestamps = false;

    protected $keyType = 'string';
}
