<?php

namespace App\Http\Middleware;

use Modules\Cms\app\Policies\CustomCspPolicy;
use Spatie\Csp\AddCspHeaders as BaseCspMiddleware;

class CspHeader extends BaseCspMiddleware
{
    public function __construct()
    {
        $csp = new CustomCspPolicy;
    }
}
