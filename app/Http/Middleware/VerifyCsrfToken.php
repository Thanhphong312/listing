<?php

namespace Vanguard\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'mobile/*',
        'api/order',
        'products/addVariant',
        'sync-desgin-driver',
        'qr/*',
        'tickets/*',
        'orders/*',
        'order/*',
        'convert-design',
        'webhook/17track',
        'webhook',
        'login',
        'flashdeals/add',
        'metas/update',
        'api/*',
        '/saved-crawls',
    ];
}
