<?php /** @noinspection ALL */

namespace App\Components\Classes\StoreFile;

use Illuminate\Validation\ValidationException;
use Storage;
use Validator;

class File
{
    /**
     * MIME-type for storage file.
     *
     * @var array
     */
    private $mime;

    /**
     * Decoded data file.
     *
     * @var bool|string
     */
    private $file;

    /**
     * File name for storage file.
     *
     * @var string
     */
    private $fname;

    /**
     * List of all available MIME-types.
     *
     * @var array
     */
    private $availableMimes;

    /**
     * File constructor.
     *
     * @param string $base64
     * @return void
     */
    public function __construct (string $base64)
    {
        $this->mime = explode('/', str_replace('data:', '', explode(';', $base64)[0]));

        $prepare = substr($base64, strpos($base64, ',') + 1);
        $this->file = base64_decode($prepare);

        $this->availableMimes = $this->parseMimes();
    }

    /**
     * Get available MIME-types
     *
     * @return array
     */
    private function parseMimes ()
    {
        $f = fopen(app_path('Components/Classes/StoreFile/mimes.csv'), 'r');
        $data = [];
        while (($a = fgetcsv($f, 0, ',')) !== false) {
            $mime = $a[0];
            unset($a[0]);
            $data[$mime] = array_values($a);
        }
        fclose($f);

        return $data;
    }

    /**
     * Validation MIME-types
     *
     * @param array $mimes
     * @return bool
     * @throws ValidationException
     */
    public function validation (array $mimes): bool
    {
        $validator = Validator::make([
            'mime' => implode('/', $this->mime)
        ], [
            'mime' => [new MimeRule($mimes, $this->availableMimes)]
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return true;
    }

    /**
     * Store file in public storage
     *
     * @param $path
     * @return null|string
     */
    public function store ($path)
    {
        if ($this->file === false) {
            return null;
        }

        $fullMime = strtolower(implode('/', $this->mime));
        if (array_key_exists($fullMime, $this->availableMimes) === false) {
            return null;
        }

        $this->fname = str_random(30) . '.' . $this->availableMimes[$fullMime][0];
        $fullPath = '/' . $path . '/' . $this->fname;
        Storage::disk('public')->put($fullPath, $this->file);

        return $fullPath;
    }
}
