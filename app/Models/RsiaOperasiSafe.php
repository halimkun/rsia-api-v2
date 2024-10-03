<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;

class RsiaOperasiSafe extends Model
{
    use HasFactory, HasCompositeKey, Compoships;

    protected $table = 'rsia_operasi_safe';

    protected $primaryKey = ['no_rawat', 'tgl_operasi', 'kode_paket'];

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'string';


    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    public function scopeWithAllRelations()
    {
        return $this->with([
            'detailPaket',
            'detailOperator1',
            'detailAsistenOperator1',
            'detailAsistenOperator2',
            'detailDokterAnak',
            'detailDokterAnestesi',
            'detailAsistenAnestesi',
            'detailOnloop',
        ]);
    }

    /**
     * Get the laporan that owns the Operasi
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function laporan()
    {
        return $this->hasOne(LaporanOperasi::class, ['no_rawat', 'tanggal'], ['no_rawat', 'tgl_operasi']);
    }

    public function detailPaket()
    {
        return $this->belongsTo(PaketOperasi::class, 'kode_paket', 'kode_paket')->select('kode_paket', 'nm_perawatan');
    }

    public function detailOperator1()
    {
        return $this->belongsTo(Dokter::class, 'operator1', 'kd_dokter')->select('kd_dokter', 'nm_dokter')->where('kd_dokter', '<>', '-');
    }

    public function detailAsistenOperator1()
    {
        return $this->belongsTo(Pegawai::class, 'asisten_operator1', 'nik')->select('id', 'nik', 'nama')->where('nik', '<>', '-');
    }

    public function detailAsistenOperator2()
    {
        return $this->belongsTo(Pegawai::class, 'asisten_operator2', 'nik')->select('id', 'nik', 'nama')->where('nik', '<>', '-');
    }

    public function  detailDokterAnak()
    {
        return $this->belongsTo(Dokter::class, 'dokter_anak', 'kd_dokter')->select('kd_dokter', 'nm_dokter')->where('kd_dokter', '<>', '-');
    }

    public function detailDokterAnestesi()
    {
        return $this->belongsTo(Dokter::class, 'dokter_anestesi', 'kd_dokter')->select('kd_dokter', 'nm_dokter')->where('kd_dokter', '<>', '-');
    }

    public function detailAsistenAnestesi()
    {
        return $this->belongsTo(Pegawai::class, 'asisten_anestesi', 'nik')->select('id', 'nik', 'nama')->where('nik', '<>', '-');
    }

    public function detailOnloop()
    {
        return $this->belongsTo(Pegawai::class, 'onloop', 'nik')->select('id', 'nik', 'nama')->where('nik', '<>', '-');
    }
}
