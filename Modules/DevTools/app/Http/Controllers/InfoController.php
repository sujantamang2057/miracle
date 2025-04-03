<?php

namespace Modules\DevTools\app\Http\Controllers;

use Modules\Common\app\Http\Controllers\BackendController;

class InfoController extends BackendController
{
    public function __construct()
    {
        // Permission For Master Role Only
        $this->middleware(HAS_ROLE_MASTER_PERMISSION)->only(['index']);
    }

    public function index()
    {
        return view('devtools::info.index');
    }
}
