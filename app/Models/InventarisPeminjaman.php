<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class InventarisPeminjaman
 * 
 * @property string $peminjam
 * @property string $tlp
 * @property string $no_inventaris
 * @property Carbon $tgl_pinjam
 * @property Carbon|null $tgl_kembali
 * @property string $nip
 * @property string|null $status_pinjam
 * 
 * @property Inventaris $inventaris
 * @property Petugas $petugas
 *
 * @package App\Models
 */
class InventarisPeminjaman extends Model
{
	/**
	 * Table name
	 *
	 * @var string
	 */
	protected $table = 'inventaris_peminjaman';

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
	 * Fillable fields
	 *
	 * @var array
	 */
	protected $casts = [
		'tgl_pinjam' => 'datetime',
		'tgl_kembali' => 'datetime'
	];

	/**
	 * Fillable fields
	 *
	 * @var array
	 */
	protected $fillable = [
		'tlp',
		'tgl_kembali',
		'status_pinjam'
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
