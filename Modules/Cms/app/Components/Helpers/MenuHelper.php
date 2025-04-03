<?php

/**
 * Menu Helper Functions
 * /Modules/Cms/app/Components/Helpers/MenuHelper.php
 */

namespace Modules\Cms\app\Components\Helpers;

use Illuminate\Support\Str;
use Modules\CmsAdmin\app\Models\Menu as MenuModel;
use Spatie\Menu\Laravel\Link;
use Spatie\Menu\Laravel\Menu;

class MenuHelper
{
    public static function menu()
    {
        $menuData = self::__getMenuData();

        return self::__generateMenu($menuData);
    }

    private static function __getMenuData()
    {
        $data = MenuModel::active()
            ->with(['children' => function ($q) {
                $q->active();
            }])
            ->whereNull(['parent_id'])
            ->where([
                ['active', '=', 1],
            ])
            ->orderBy('show_order', 'DESC')
            ->get()->toArray();

        return $data;
    }

    private static function __generateMenu($data)
    {
        $menu = Menu::new()
            ->addClass('nav nav-menu align-items-center');
        if (!empty($data) && is_array($data)) {
            foreach ($data as $key => $item) {
                $menu = self::__addMenuItem($menu, $item);
            }
        }

        return $menu;
    }

    private static function __addMenuItem($menuObj, $item, $childItem = false)
    {
        $url = isset($item['url']) ? $item['url'] : '';
        $urlType = isset($item['url_type']) ? $item['url_type'] : '';
        $urlTarget = isset($item['url_target']) ? $item['url_target'] : '';
        $menuTitle = isset($item['title']) ? $item['title'] : '';
        $children = isset($item['children']) ? $item['children'] : '';
        $activeClass = setActiveMenuCms($url . '*');

        // Make only one slash between the domain and the path
        $fullUrl = ($urlType == 1) ? baseURL() . '/' . Str::of($url)->ltrim('/') : Str::of($url)->trim('/');

        if (!empty($children) && is_array($children)) {
            $childMenu = Menu::new()->setAttribute('class', 'dropdown-menu');
            foreach ($children as $key => $child) {
                $childMenu = self::__addMenuItem($childMenu, $child, true);
            }

            $menuObj->submenu(Link::to($fullUrl, $menuTitle)
                ->addClass('nav-link dropdown-toggle')
                ->setAttribute('data-bs-toggle', 'dropdown'), $childMenu->addParentClass('nav-item dropdown'));
        } else {
            $menuObj->add(Link::to($fullUrl, $menuTitle)
                ->addClass($childItem ? 'nav-link text-dark' : 'nav-link')
                ->addParentClass('nav-item ' . $activeClass)
                ->setAttribute('target', ($urlTarget != 2) ? '_self' : '_blank'));
        }

        return $menuObj;
    }
}
