<?php

namespace App\Components\Traits\Roles;

use App\Role;

trait RolesManipulator
{
    /**
     * Assign role to user
     *
     * @param string $alias
     * @return bool
     */
    public function assignRole ($alias)
    {
        $role = Role::where('name', $alias)->first();
        if (empty($role)) {
            return false;
        }

        $this->role()->associate($role);
        $this->save();
        return true;
    }

    /**
     * Belongs to relationship
     *
     * @abstract
     * @return mixed
     */
    abstract public function role ();

    /**
     * Associate role without saving model
     *
     * @param $alias
     * @return bool
     */
    public function prepareAssignRole ($alias)
    {
        $role = Role::where('name', $alias)->first();
        if (empty($role))
            return false;

        $this->role()->associate($role);
        return true;
    }

    /**
     * Check role
     *
     * @param array|string $alias
     * @return bool
     */
    public function hasRole ($alias)
    {
        if (empty($this->role)) {
            return false;
        }

        if (is_string($alias)) {
            if ($this->role->name != $alias) {
                return false;
            }
        } else if (is_array($alias)) {
            return array_search($this->role->name, $alias, true) !== false;
        } else {
            return false;
        }
        return true;
    }

    /**
     * Delete user role
     *
     * @return bool
     */
    public function removeRole ()
    {
        if (empty($this->role)) {
            return false;
        }

        $this->role()->dissociate();
        $this->save();
        return true;
    }

    /**
     * Get user displayed role name
     *
     * @return bool|string
     */
    public function getRoleName ()
    {
        if (empty($this->role)) {
            return false;
        }

        return $this->role->display_name;
    }

    /**
     * Get user system role name
     *
     * @return bool|string
     */
    public function getRole ()
    {
        if (empty($this->role)) {
            return false;
        }

        return $this->role->name;
    }
}
