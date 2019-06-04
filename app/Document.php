<?php

namespace App;

use App\Components\Traits\Storage\Files as StorageTrait;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use StorageTrait;

    /**
     * @var array
     */
    protected $fillable = [
        'file', 'documentable_id', 'documentable_type'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function documentable ()
    {
        return $this->morphTo();
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function add (array $attributes)
    {
        return $this->create($attributes);
    }

    /**
     * Update drivers document
     *
     * @param array $attributes
     * @return bool
     */
    public function change (array $attributes)
    {
        $this->update($attributes);
        return true;
    }

    public function setFileAttribute (string $file)
    {
        $file = self::setFile($file, 'documents');

        return $this->attributes['file'] = $file;
    }
}
