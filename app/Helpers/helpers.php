<?php

use Illuminate\Support\Facades\View;

if (!function_exists('isActiveMenu')) {
    /**
     * Check if a menu or any of its children is active.
     *
     * @param array $menu
     * @return bool
     */
    function isActiveMenu(array $menu): bool
    {
        if (View::hasSection($menu['key'])) {
            return true;
        }

        if (!empty($menu['children'])) {
            foreach ($menu['children'] as $child) {
                if (isActiveMenu($child)) {
                    return true;
                }
            }
        }

        return false;
    }
}