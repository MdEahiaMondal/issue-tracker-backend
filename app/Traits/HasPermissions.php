<?php

namespace App\Traits;

use App\Models\Permission;

trait HasPermissions
{

    public function hasRoles(...$roles): int
    {
        return $this->roles()->whereIn('slug', $roles)->count();
    }

    public function hasPermission(...$permissions)
    {
        return $this->permissions()->whereIn('slug', $permissions)->count() ||
            $this->roles()->whereHas('permissions', function ($q) use ($permissions) {
                $q->whereIn('slug', $permissions);
            })->count();
    }

    private function getPermissionIdsBySlug($permissions)
    {
        return Permission::whereIn('slug', $permissions)->get()->pluck('id')->toArray();
    }

    public function givePermissionsTo(...$permissions)
    {
        $this->permissions()->attach($this->getPermissionIdsBySlug($permissions));
    }

    public function setPermissionsTo(...$permissions)
    {
        $this->permissions()->sync($this->getPermissionIdsBySlug($permissions));
    }

    public function removePermissions(...$permissions)
    {
        $this->permissions()->detach($this->getPermissionIdsBySlug($permissions));
    }
}
