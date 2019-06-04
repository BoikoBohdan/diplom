<?php


namespace App\Components\Traits\Permissions;


use App\User;

trait UserPermissions
{
    /**
     * @var bool
     */
    private $condition = true;

    /**
     * Check if authentificated admin can edit another admin
     *
     * @param User $admin
     * @return bool
     */
    public function canUpdateUser (User $admin)
    {
        $adminRole = $admin->role; // touched user role
        $currentRole = $this->role; // auth user role

        $this->touchMasterAdmin($adminRole, $currentRole);

        $this->touchCompanyAdmin($this, $admin);

        $this->touchCompanyDriver($this, $admin);

        $this->touchDriver($currentRole, $adminRole);

        return $this->condition;
    }

    /**
     * Check if user touches master admin
     *
     * @param $user
     * @param $touched
     * @return bool
     */
    private function touchMasterAdmin ($user, $touched)
    {
        return $user->isSuperAdmin() && $touched->isMasterAdmin()
            ? $this->condition = false
            : $this->condition = true;
    }

    /**
     * Check if two admins are from the same company
     *
     * @param $user
     * @param $touched
     * @return bool
     */
    public function touchCompanyAdmin ($user, $touched)
    {
        return $user->role->isAdmin() && $touched->role->isAdmin()
        && $touched->company_id === $user->company_id
            ? $this->condition = true
            : $this->condition = false;
    }

    /**
     * Check if admin can edit drivers from his company
     *
     * @param $user
     * @param $touched
     * @return bool
     */
    public function touchCompanyDriver ($user, $touched)
    {
        return $user->role->isAdmin() && $touched->role->isDriver()
        && $touched->company_id === $user->company_id
            ? $this->condition = true
            : $this->condition = false;
    }

    /**
     * Check if super admin touches driver
     *
     * @param $user
     * @param $touched
     * @return bool
     */
    public function touchDriver ($user, $touched)
    {
        return $user->isSuperAdmin() && $touched->isDriver()
            ? $this->condition = false
            : $this->condition = true;
    }

    /**
     * Check if user can create order
     *
     * @return bool
     */
    public function canCreateOrder ()
    {
        return !$this->role->isAdmin();
    }

    /**
     * Check if user can delete model
     *
     * @param User $admin
     * @return bool
     */
    public function canDeleteUser (User $admin)
    {
        $adminRole = $admin->role;
        $currentRole = $this->role;

        $this->touchMasterAdmin($currentRole, $adminRole);

        $this->touchCompanyAdmin($this, $admin);

        $this->touchDriver($currentRole, $adminRole);

        return $this->condition;
    }

    /**
     * Check if user can update order
     *
     * @return bool
     */
    public function canUpdateOrder ()
    {
        return !$this->role->isDriver();
    }

    /**
     * Check if user can delete order
     *
     * @return bool
     */
    public function canDeleteOrder ()
    {
        return false;
    }

    /**
     * Check if user can assign order in gods eye
     *
     * @return bool
     */
    public function canAssignOrder ()
    {
        return !$this->role->isSuperAdmin();
    }

    /**
     * Check if user can create Shift
     *
     * @return bool
     */
    public function canCreateShift ()
    {
        return !$this->role->isSuperAdmin();
    }

    /**
     * Check if user can update Shift
     *
     * @return bool
     */
    public function canUpdateShift ()
    {
        return !$this->role->isSuperAdmin();
    }

    /**
     * Check if user can delete shift
     *
     * @return bool
     */
    public function canDeleteShift ()
    {
        return !$this->role->isSuperAdmin();
    }

    /**
     * Check if user can create driver
     *
     * @return bool
     */
    public function canCreateDriver ()
    {
        return !$this->role->isDriver();
    }

    /**
     * Check if user can access chat page
     *
     * @return bool
     */
    public function canViewChat ()
    {
        return !$this->role->isSuperAdmin();
    }

}
