<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PermintaanPerbaikanInventaris
 * 
 * @property string $no_permintaan
 * @property string|null $no_inventaris
 * @property string|null $nik
 * @property Carbon|null $tanggal
 * @property string|null $deskripsi_kerusakan
 * 
 * @property Inventaris|null $inventaris
 * @property Pegawai|null $pegawai
 * @property PerbaikanInventaris $perbaikan_inventaris
 *
 * @package App\Models
 */
class PermintaanPerbaikanInventaris extends Model
{
	/**
	 * Table name
	 *
	 * @var string
	 */
	protected $table = 'permintaan_perbaikan_inventaris';

	/**
	 * Primary key
	 *
	 * @var string
	 */
	protected $primaryKey = 'no_permintaan';

	/**
	 * Timestamps
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
	 * Fillable column
	 *
	 * @var array
	 */
	protected $casts = [
		'tanggal' => 'datetime'
	];

	/**
	 * Fillable column
	 *
	 * @var array
	 */
	protected $fillable = [
		'no_inventaris',
		'nik',
		'tanggal',
		'deskripsi_kerusakan'
	];

	/**
	 * Inventaris relation
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function inventaris()
	{
		return $this->belongsTo(Inventaris::class, 'no_inventaris');
	}

	/**
	 * Pegawai relation
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function pegawai()
	{
		return $this->belongsTo(Pegawai::class, 'nik', 'nik');
	}

	/**
	 * Perbaikan inventaris relation
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function perbaikan_inventaris()
	{
		return $this->hasOne(PerbaikanInventaris::class, 'no_permintaan');
	}
}
