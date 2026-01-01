<?php

if (!function_exists('hasPermission')) {
    /**
     * Check if the authenticated user has a specific permission.
     *
     * @param string $permission Permission name (e.g., 'view.patients')
     * @return bool
     */
    function hasPermission(string $permission): bool
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }

        return $user->can($permission);
    }
}

if (!function_exists('hasAnyPermission')) {
    /**
     * Check if the authenticated user has any of the given permissions.
     *
     * @param array $permissions Array of permission names
     * @return bool
     */
    function hasAnyPermission(array $permissions): bool
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }

        foreach ($permissions as $permission) {
            if ($user->can($permission)) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('hasAllPermissions')) {
    /**
     * Check if the authenticated user has all of the given permissions.
     *
     * @param array $permissions Array of permission names
     * @return bool
     */
    function hasAllPermissions(array $permissions): bool
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }

        foreach ($permissions as $permission) {
            if (!$user->can($permission)) {
                return false;
            }
        }

        return true;
    }
}

