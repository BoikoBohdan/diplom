<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    public const GENERAL_COMPANY = 'Just Eat';

    /**
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users ()
    {
        return $this->hasMany(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders ()
    {
        return $this->hasMany(Order::class, 'group_guid', 'guid');
    }
}
