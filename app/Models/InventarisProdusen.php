<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class InventarisProdusen
 * 
 * @property string $kode_produsen
 * @property string|null $nama_produsen
 * @property string|null $alamat_produsen
 * @property string|null $no_telp
 * @property string|null $email
 * @property string|null $website_produsen
 * 
 * @property Collection|InventarisBarang[] $inventaris_barangs
 *
 * @package App\Models
 */
class InventarisProdusen extends Model
{
	/**
	 * Table name
	 *
	 * @var string
	 */
	protected $table = 'inventaris_produsen';

	/**
	 * Primary key
	 *
	 * @var string
	 */
	protected $primaryKey = 'kode_produsen';

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
		'nama_produsen',
		'alamat_produsen',
		'no_telp',
		'email',
		'website_produsen'
	];

	/**
	 * Inventaris barang relation
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function barang()
	{
		return $this->hasMany(InventarisBarang::class, 'kode_produsen');
	}
}
