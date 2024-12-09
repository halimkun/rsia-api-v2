<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Inventari
 * 
 * @property string $no_inventaris
 * @property string|null $kode_barang
 * @property string|null $asal_barang
 * @property Carbon|null $tgl_pengadaan
 * @property float|null $harga
 * @property string|null $status_barang
 * @property string|null $id_ruang
 * @property string|null $no_rak
 * @property string|null $no_box
 * 
 * @property InventarisBarang|null $inventaris_barang
 * @property InventarisRuang|null $inventaris_ruang
 * @property CssdBarang $cssd_barang
 * @property InventarisGambar $inventaris_gambar
 * @property Collection|InventarisPeminjaman[] $inventaris_peminjamen
 * @property Collection|PemeliharaanInventari[] $pemeliharaan_inventaris
 * @property Collection|PermintaanPerbaikanInventari[] $permintaan_perbaikan_inventaris
 *
 * @package App\Models
 */
class Inventaris extends Model
{
	/**
	 * Table name
	 *
	 * @var string
	 */
	protected $table = 'inventaris';

	/**
	 * Primary key
	 *
	 * @var string
	 */
	protected $primaryKey = 'no_inventaris';

	/**
	 * Auto increment
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
	 * @var string[]
	 */
	protected $casts = [
		'tgl_pengadaan' => 'datetime',
		'harga' => 'float'
	];

	/**
	 * Fillable column
	 *
	 * @var string[]
	 */
	protected $fillable = [
		'kode_barang',
		'asal_barang',
		'tgl_pengadaan',
		'harga',
		'status_barang',
		'id_ruang',
		'no_rak',
		'no_box'
	];


	/**
	 * Inventaris barang relation
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function barang()
	{
		return $this->belongsTo(InventarisBarang::class, 'kode_barang');
	}

	/**
	 * Inventaris ruang relation
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function ruang()
	{
		return $this->belongsTo(InventarisRuang::class, 'id_ruang');
	}

	/**
	 * Cssd barang relation
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	// public function cssd_barang()
	// {
	// 	return $this->hasOne(CssdBarang::class, 'no_inventaris');
	// }

	/**
	 * Inventaris gambar relation
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function gambar()
	{
		return $this->hasOne(InventarisGambar::class, 'no_inventaris');
	}

	/**
	 * Inventaris peminjamen relation
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function peminjamen()
	{
		return $this->hasMany(InventarisPeminjaman::class, 'no_inventaris');
	}

	/**
	 * Pemeliharaan inventaris relation
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function pemeliharaan()
	{
		return $this->hasMany(PemeliharaanInventaris::class, 'no_inventaris');
	}

	/**
	 * Permintaan perbaikan inventaris relation
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function permintaan_perbaikan()
	{
		return $this->hasMany(PermintaanPerbaikanInventaris::class, 'no_inventaris');
	}
}
