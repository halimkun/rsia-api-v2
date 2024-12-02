<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class InventarisBarang
 * 
 * @property string $kode_barang
 * @property string|null $nama_barang
 * @property int|null $jml_barang
 * @property string|null $kode_produsen
 * @property string|null $id_merk
 * @property Carbon|null $thn_produksi
 * @property string|null $isbn
 * @property string|null $id_kategori
 * @property string|null $id_jenis
 * 
 * @property InventarisProdusen|null $inventaris_produsen
 * @property InventarisMerk|null $inventaris_merk
 * @property InventarisKategori|null $inventaris_kategori
 * @property InventarisJenis|null $inventaris_jenis
 * @property Collection|Inventaris[] $inventaris
 * @property Collection|InventarisDetailBeli[] $inventaris_detail_belis
 * @property Collection|InventarisDetailHibah[] $inventaris_detail_hibahs
 * @property InventarisDetailPesan $inventaris_detail_pesan
 *
 * @package App\Models
 */
class InventarisBarang extends Model
{
	/**
	 * Table name
	 *
	 * @var string
	 */
	protected $table = 'inventaris_barang';

	/**
	 * Primary key
	 *
	 * @var string
	 */
	protected $primaryKey = 'kode_barang';

	/**
	 * Auto increment
	 *
	 * @var bool
	 */
	public $incrementing = false;

	/**
	 * Timestamp
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
		'jml_barang' => 'int',
		'thn_produksi' => 'datetime'
	];

	/**
	 * Fillable column
	 *
	 * @var string[]
	 */
	protected $fillable = [
		'nama_barang',
		'jml_barang',
		'kode_produsen',
		'id_merk',
		'thn_produksi',
		'isbn',
		'id_kategori',
		'id_jenis'
	];


	/**
	 * InventarisProdusen relation
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function inventaris_produsen()
	{
		return $this->belongsTo(InventarisProdusen::class, 'kode_produsen');
	}

	/**
	 * InventarisMerk relation
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function inventaris_merk()
	{
		return $this->belongsTo(InventarisMerk::class, 'id_merk');
	}

	/**
	 * InventarisKategori relation
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function inventaris_kategori()
	{
		return $this->belongsTo(InventarisKategori::class, 'id_kategori');
	}

	/**
	 * InventarisJenis relation
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function inventaris_jenis()
	{
		return $this->belongsTo(InventarisJenis::class, 'id_jenis');
	}

	/**
	 * Inventaris relation
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function inventaris()
	{
		return $this->hasMany(Inventaris::class, 'kode_barang');
	}

	/**
	 * InventarisDetailBeli relation
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	// public function inventaris_detail_belis()
	// {
	// 	return $this->hasMany(InventarisDetailBeli::class, 'kode_barang');
	// }

	/**
	 * InventarisDetailHibah relation
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	// public function inventaris_detail_hibahs()
	// {
	// 	return $this->hasMany(InventarisDetailHibah::class, 'kode_barang');
	// }

	/**
	 * InventarisDetailPesan relation
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	// public function inventaris_detail_pesan()
	// {
	// 	return $this->hasOne(InventarisDetailPesan::class, 'kode_barang');
	// }
}
