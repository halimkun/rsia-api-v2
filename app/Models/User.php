<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    private static $password_key = 'windi';
    
    private static $username_key = 'nur';

    protected $table = 'user';

    protected $primaryKey = 'id_user';

    public $timestamps = false;

    public $incrementing = false;

    protected $keyType = 'string';


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime'
    ];

    public function getAuthPassword()
    {
        $password = DB::select("SELECT AES_ENCRYPT(?, ?) as password", [$this->password, self::$password_key])[0]->password;
        dd($password);
        
        return $password;
    }

    public function validateForPassportPasswordGrant(string $password)
    {
        // mariadb aes_decrypt function
        $password = DB::select("SELECT AES_DECRYPT(?, ?) as password", [$password, self::$password_key])[0]->password;
        dd($password);
        
        return $password === $this->password;
    }
}
