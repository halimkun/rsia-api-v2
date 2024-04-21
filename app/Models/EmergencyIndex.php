<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\EmergencyIndex
 *
 * @property string $kode_emergency
 * @property string|null $nama_emergency
 * @property int|null $indek
 * @method static \Illuminate\Database\Eloquent\Builder|EmergencyIndex newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmergencyIndex newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmergencyIndex query()
 * @method static \Illuminate\Database\Eloquent\Builder|EmergencyIndex whereIndek($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmergencyIndex whereKodeEmergency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmergencyIndex whereNamaEmergency($value)
 * @mixin \Eloquent
 */
class EmergencyIndex extends Model
{
    use HasFactory;

    protected $table = "emergency_index";

    protected $primaryKey = "kode_emergency";

    protected $guarded = [];

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;
}
