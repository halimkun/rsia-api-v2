<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class InventarisMerk
 * 
 * @property string $id_merk
 * @property string $nama_merk
 * 
 * @property Collection|InventarisBarang[] $inventaris_barangs
 *
 * @package App\Models
 */
class InventarisMerk extends Model
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'inventaris_merk';

	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'id_merk';

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
		'nama_merk'
	];

	/**
	 * Inventaris barang relation
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function inventaris_barangs()
	{
		return $this->hasMany(InventarisBarang::class, 'id_merk');
	}
}
