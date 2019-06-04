<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public const NAMES = [
        1 => 'master_admin',
        2 => 'super_admin',
        3 => 'admin',
        4 => 'driver'
    ];

    public const DRIVER_NAME = 'driver';
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'display_name'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users ()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Check if user is Master admin
     *
     * @return bool
     */
    public function isMasterAdmin (): bool
    {
        return $this->name === 'master_admin';
    }

    /**
     * Check if user is Super Admin
     *
     * @return bool
     */
    public function isSuperAdmin (): bool
    {
        return $this->name === 'super_admin';
    }

    /**
     * Check if user is Admin
     *
     * @return bool
     */
    public function isAdmin (): bool
    {
        return $this->name === 'admin';
    }

    /**
     * Check if user is Driver
     *
     * @return bool
     */
    public function isDriver (): bool
    {
        return $this->name === 'driver';
    }
}
