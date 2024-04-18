<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class AesUserProvider extends EloquentUserProvider
{
    // protected $aesHasher;

    // public function __construct()
    // {
    //     $this->aesHasher = new \App\Services\AesHasher();    
    // }

    public function retrieveById($identifier)
    {
        $model = $this->createModel();
        $aesHasher = new \App\Services\AesHasher();

        return $this->newModelQuery($model)
            ->select(
                DB::raw($aesHasher->decrypt($model->getAuthIdentifierName(), ['key' => env('MYSQL_AES_KEY_IDUSER')]) . ' as ' . $model->getAuthIdentifierName()),
                DB::raw($aesHasher->decrypt("password", ['key' => env('MYSQL_AES_KEY_PASSWORD')]) . ' as ' . "password")
            )
            ->where($model->getAuthIdentifierName(), DB::raw($this->hasher->make($identifier, ['key' => env('MYSQL_AES_KEY_IDUSER')])))
            ->first();
    }

    public function retrieveByToken($identifier, $token)
    {
        $model = $this->createModel();

        $retrievedModel = $this->newModelQuery($model)->where(
            $model->getAuthIdentifierName(),
            $this->hasher->make($identifier, ['key' => env('MYSQL_AES_KEY_IDUSER')])
        )->first();

        if (!$retrievedModel) {
            return;
        }

        $rememberToken = $retrievedModel->getRememberToken();

        return $rememberToken && hash_equals($rememberToken, $token)
            ? $retrievedModel : null;
    }
}
