<?php

namespace App\Models;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class RsiaOtp extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rsia_otp';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'app_id',
        'nik',
        'otp',
        'expired_at',
        'is_used',
    ];

    protected $hidden = ['id'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    public static function createOtp($data)
    {
        $data['otp'] = Hash::make($data['otp']);
        return self::create($data);
    }

    /**
     * Check if the OTP is valid
     *
     * @param string $otp
     * @return bool
     */
    public function isValidOtp($otp)
    {
        return Hash::check($otp, $this->otp) && !$this->is_used && $this->expired_at > \Carbon\Carbon::now();
    }
}
