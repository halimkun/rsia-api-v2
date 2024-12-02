<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PemeliharaanInventaris
 * 
 * @property string $no_inventaris
 * @property Carbon $tanggal
 * @property string $uraian_kegiatan
 * @property string $nip
 * @property string $pelaksana
 * @property float $biaya
 * @property string $jenis_pemeliharaan
 * 
 * @property Inventaris $inventaris
 * @property Petugas $petugas
 *
 * @package App\Models
 */
class PemeliharaanInventaris extends Model
{
	/**
	 * Table name
	 *
	 * @var string
	 */
	protected $table = 'pemeliharaan_inventaris';

	/**
	 * Primary key
	 *
	 * @var string
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
		'tanggal' => 'datetime',
		'biaya' => 'float'
	];

	/**
	 * Fillable column
	 *
	 * @var array
	 */
	protected $fillable = [
		'uraian_kegiatan',
		'nip',
		'pelaksana',
		'biaya',
		'jenis_pemeliharaan'
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
	 * Petugas relation
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function petugas()
	{
		return $this->belongsTo(Petugas::class, 'nip');
	}
}
