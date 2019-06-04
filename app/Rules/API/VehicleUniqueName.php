<?php

namespace App\Rules\API;

use App\Vehicle;
use Illuminate\Contracts\Validation\Rule;

class VehicleUniqueName implements Rule
{
    protected $name;
    protected $driver;
    protected $id;
    protected $method;

    public function __construct ($id, $name, $driver, $method)
    {
        $this->id = $id;
        $this->name = $name;
        $this->driver = $driver;
        $this->method = $method;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes ($attribute, $value): bool
    {
        if ((isset($this->id) && $this->method === 'PUT') || $this->method === 'PATCH') {
            return Vehicle::where('name', $this->name)
                    ->where('id', $this->id)->count() != 0;
        }

        return Vehicle::where('name', $this->name)->where('driver_id', $this->driver)->count() === 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message (): string
    {
        return 'The name is already in use';
    }
}
