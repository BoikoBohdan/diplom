<?php

namespace App\Components\Traits;

use Illuminate\Support\Facades\Cache;

trait Cachable
{
    public function setForeverCacheValue (string $key, $values)
    {
        Cache::forever($key, $values);
    }
}
