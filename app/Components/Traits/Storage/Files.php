<?php

namespace App\Components\Traits\Storage;

use App\Components\Classes\StoreFile\File;

trait Files
{
    /**
     * Array of image mimes
     *
     * @return array
     */
    public static function imageMimes ()
    {
        return [
            'jpg', 'png', 'svg', 'jpeg'
        ];
    }

    /**
     * Array of file mimes
     *
     * @return array
     */
    public static function fileMimes ()
    {
        return [
            'doc', 'docx', 'csv', 'xlsx', 'txt', 'ppt', 'zip', 'rar'
        ];
    }

    /**
     * @param $item
     * @param $path
     * @return string|null
     * @throws \Illuminate\Validation\ValidationException
     */
    protected static function setFile ($item, $path)
    {
        $file = new File($item);
        $file->validation(['jpg', 'png', 'svg']);
        $item = $file->store($path);

        return $item;
    }

    /**
     * @param $item
     * @param $path
     * @return string|null
     * @throws \Illuminate\Validation\ValidationException
     */
    protected static function setChatFile ($item, $path)
    {
        $file = new File($item);
        $file->validation(self::chatMimes());
        $item = $file->store($path);

        return $item;
    }

    /**
     * @return array
     */
    public static function chatMimes ()
    {
        return [
            'jpg', 'png', 'svg', 'doc', 'docx', 'csv', 'xlsx', 'txt', 'ppt', 'zip', 'rar'
        ];
    }

    /**
     * Check if file mime is file or image
     *
     * @param array $mimes
     * @param string $messageMime
     * @return bool
     */
    public function checkMimeType (array $mimes, string $messageMime)
    {
        return collect($mimes)->contains(static function ($mime) use ($messageMime) {
            return $messageMime === $mime;
        });
    }
}
