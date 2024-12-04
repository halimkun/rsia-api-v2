<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PerbaikanInventaris
 * 
 * @property string $no_permintaan
 * @property Carbon $tanggal
 * @property string $uraian_kegiatan
 * @property string $nip
 * @property string $pelaksana
 * @property float $biaya
 * @property string $keterangan
 * @property string $status
 * 
 * @property PermintaanPerbaikanInventaris $permintaan_perbaikan_inventaris
 * @property Petugas $petugas
 *
 * @package App\Models
 */
class PerbaikanInventaris extends Model
{
	/**
	 * Table name
	 *
	 * @var string
	 */
	protected $table = 'perbaikan_inventaris';

	/**
	 * Primary key
	 *
	 * @var string
	 */
	protected $primaryKey = 'no_permintaan';

	/**
	 * Auto-increment
	 *
	 * @var bool
	 */
	public $incrementing = false;

	/**
	 * Timestamps
	 *
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * Fillable fields
	 *
	 * @var array
	 */
	protected $casts = [
		'tanggal' => 'datetime',
		'biaya' => 'float'
	];

	/**
	 * Fillable fields
	 *
	 * @var array
	 */
	protected $fillable = [
		'tanggal',
		'uraian_kegiatan',
		'nip',
		'pelaksana',
		'biaya',
		'keterangan',
		'status'
	];

	/**
	 * Permintaan perbaikan inventaris relation
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function permintaan_perbaikan()
	{
		return $this->belongsTo(PermintaanPerbaikanInventaris::class, 'no_permintaan');
	}

	/**
	 * Petugas relation
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function petugas()
	{
		return $this->belongsTo(Petugas::class, 'nip');
	}
}
