<?php

namespace App\Http\Middleware;

use App\Support\SifecTransientFlashCookie;
use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

class EncryptCookies extends Middleware
{
    /**
     * The names of the cookies that should not be encrypted.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Valeur déjà signée (HMAC) ; le chiffrement Laravel peut empêcher la lecture côté Blade si le déchiffrement échoue.
        SifecTransientFlashCookie::NAME,
    ];
}
