<?php

namespace Modules\DevTools\app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Modules\Common\app\Http\Controllers\BackendController;

class RouteListController extends BackendController
{
    public function __construct()
    {
        // Permission For Master Role Only
        $this->middleware(HAS_ROLE_MASTER_PERMISSION)->only(['index', 'route']);
    }

    public function index(Request $request)
    {
        $type = $request->type;

        return view('devtools::route_lists.index', ['routes' => $this->__getRoutesByType($type)]);
    }

    // handle ajax request to render
    public function route(Request $request)
    {
        $type = $request->type;

        return response()->json([
            'html' => view('devtools::route_lists.list', ['routes' => $this->__getRoutesByType($type)])->render(),
        ]);
    }

    private function __getRoutesByType($type)
    {
        return collect(Route::getRoutes())->filter(function ($route) use ($type) {
            $controller = $route->action['controller'] ?? '';

            if ($type === 'custom') {
                return Str::startsWith($controller, 'Modules\\');
            } elseif ($type === 'vendor') {
                return !Str::startsWith($controller, 'App\\') && !Str::startsWith($controller, 'Modules\\');
            }

            return isset($route->action['controller']);
        })->values()->all();
    }
}
