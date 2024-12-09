<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class InventarisRuang
 * 
 * @property string $id_ruang
 * @property string $nama_ruang
 * 
 * @property Collection|Inventaris[] $inventaris
 * @property Collection|PeminjamanBerkas[] $peminjaman_berkas
 *
 * @package App\Models
 */
class InventarisRuang extends Model
{
	/**
	 * Table name
	 *
	 * @var string
	 */
	protected $table = 'inventaris_ruang';

	/**
	 * Primary key
	 *
	 * @var string
	 */
	protected $primaryKey = 'id_ruang';

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
	protected $fillable = [
		'nama_ruang'
	];

	/**
	 * Inventaris relation
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function inventaris()
	{
		return $this->hasMany(Inventaris::class, 'id_ruang');
	}

	/**
	 * Peminjaman berkas relation
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	// public function peminjaman_berkas()
	// {
	// 	return $this->hasMany(PeminjamanBerkas::class, 'id_ruang');
	// }
}
