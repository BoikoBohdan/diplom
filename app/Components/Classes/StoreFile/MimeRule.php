<?php /** @noinspection ALL */

namespace App\Components\Classes\StoreFile;

use Illuminate\Contracts\Validation\Rule;

class MimeRule implements Rule
{
    /**
     * @var array
     */
    private $mimesRule;


    /**
     * @var array
     */
    private $availableMimes;

    /**
     * Create a new rule instance.
     *
     * @param array $mimes
     * @param array $availableMimes
     * @return void
     */
    public function __construct (array $mimes, array $availableMimes)
    {
        $this->mimesRule = $mimes;
        $this->availableMimes = $availableMimes;
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
        $mime = explode('/', $value);
        if (count($mime) != 2)
            return false;

        if (empty($this->availableMimes))
            return false;

        if (array_key_exists(strtolower($value), $this->availableMimes) === false)
            return false;

        $exts = $this->availableMimes[$value];
        foreach ($exts as $ext) {
            if (array_search($ext, $this->mimesRule) !== false)
                return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message ()
    {
        return 'Invalid file format.';
    }
}
