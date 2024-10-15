<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetReturJual extends Model
{
    use HasFactory;

    protected $table = 'detreturjual';

    protected $primaryKey = 'no_retur_jual';

    public $incrementing = false;

    public $timestamps = false;

    // cast
    protected $casts = [
        'no_retur_jual' => 'string',
        'h_retur'       => 'float',
        'subtotal'      => 'float',
    ];


    // obat
    public function obat()
    {
        return $this->belongsTo(DataBarang::class, 'kode_brng', 'kode_brng');
    }
}
