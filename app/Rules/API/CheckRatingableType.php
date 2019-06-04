<?php

namespace App\Rules\API;

use App\Rating;
use Illuminate\Contracts\Validation\Rule;

class CheckRatingableType implements Rule
{

    protected $type;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct (string $type)
    {
        $this->type = $type;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes ($attribute, $value)
    {
        return $this->type === Rating::RESTAURANT || $this->type === Rating::CUSTOMER;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message ()
    {
        return 'Ratingable type is not valid';
    }
}
