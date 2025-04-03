<?php

namespace Modules\Common\app\Http\Controllers;

use App\Http\Controllers\Controller;

class CommonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort(404);
    }
}
