<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RsiaFileManager extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'rsia_file_manager';

    protected $fillable = [
        'nama_file', 'file'
    ];
    
    protected $primaryKey = 'id';
}
