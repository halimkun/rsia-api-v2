<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckForClaim
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next, $claim, $value = null)
    {
        /* check for presence of token */
        if (!($token = $request->bearerToken())) {
            throw new \Illuminate\Auth\AuthenticationException;
        }

        /* check if token parses properly */
        try {
            $jwt = (\Lcobucci\JWT\Configuration::forSymmetricSigner(
                new \Lcobucci\JWT\Signer\Rsa\Sha256(),
                \Lcobucci\JWT\Signer\Key\InMemory::plainText('empty', 'empty')
            )->parser()->parse($token));
        } catch (\Exception $e) {
            throw new \Illuminate\Auth\AuthenticationException;
        }

        /* check if we want to check both claim and value */
        if ($jwt->claims()->has($claim)) {

            if ($value === null) {
                return $next($request);
            }

            $explodedValue = explode('|', $value);

            if (in_array($jwt->claims()->get($claim), $explodedValue, true)) {
                return $next($request);
            }
        }

        throw new \Illuminate\Auth\AuthenticationException('Unauthenticated: missing claim');
    }
}
