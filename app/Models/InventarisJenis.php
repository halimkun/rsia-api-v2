<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class InventarisJeni
 * 
 * @property string $id_jenis
 * @property string|null $nama_jenis
 * 
 * @property AkunAsetInventari $akun_aset_inventari
 * @property Collection|InventarisBarang[] $inventaris_barangs
 *
 * @package App\Models
 */
class InventarisJenis extends Model
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'inventaris_jenis';

	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'id_jenis';

	/**
	 * Incrementing
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
	 * Fillable
	 * 
	 * @var array
	 */
	protected $fillable = [
		'nama_jenis'
	];

	/**
	 * Akun aset inventari relation 
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	// public function akun_aset_inventari()
	// {
	// 	return $this->hasOne(AkunAsetInventari::class, 'id_jenis');
	// }

	/**
	 * Inventaris barang relation
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function inventaris_barangs()
	{
		return $this->hasMany(InventarisBarang::class, 'id_jenis');
	}
}
