<?php

namespace Modules\Tools\app\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;

class ToolsController extends Controller
{
    public $moduleName;

    use ValidatesRequests;

    public function index()
    {
        return view('tools::default.index');
    }
}
