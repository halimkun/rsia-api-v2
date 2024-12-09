<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class InventarisGambar
 * 
 * @property string $no_inventaris
 * @property string|null $photo
 * 
 * @property Inventaris $inventaris
 *
 * @package App\Models
 */
class InventarisGambar extends Model
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'inventaris_gambar';

	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'no_inventaris';

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
		'photo'
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
}
