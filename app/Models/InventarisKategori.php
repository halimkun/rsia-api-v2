<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class InventarisKategori
 * 
 * @property string $id_kategori
 * @property string|null $nama_kategori
 * 
 * @property Collection|InventarisBarang[] $inventaris_barangs
 *
 * @package App\Models
 */
class InventarisKategori extends Model
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'inventaris_kategori';

	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'id_kategori';

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
		'nama_kategori'
	];

	/**
	 * Inventaris barang relation
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function inventaris_barangs()
	{
		return $this->hasMany(InventarisBarang::class, 'id_kategori');
	}
}
