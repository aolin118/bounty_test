<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
        '/657492216:AAHcY1vdwp7H33JtwzrYlVKu2qCznzCSJ2o/webhook',
        '/657492216:AAHcY1vdwp7H33JtwzrYlVKu2qCznzCSJ2o/webhook2'
    ];
}
