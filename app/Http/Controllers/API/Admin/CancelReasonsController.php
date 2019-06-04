<?php

namespace App\Http\Controllers\API\Admin;

use App\{CancelReason,
    Components\Traits\Cachable,
    Http\Controllers\Controller,
    Http\Resources\CancelReasons\Collection};
use Illuminate\Support\Facades\Cache;

class CancelReasonsController extends Controller
{
    use Cachable;

    /**
     * @return \Illuminate\Cache\CacheManager|mixed
     * @throws \Exception
     */
    public function index ()
    {
        !Cache::has('cancel-reasons')
            ? $this->setForeverCacheValue('cancel-reasons', new Collection(CancelReason::with('additionalCancelReason')->get()))
            : false;

        return cache('cancel-reasons');
    }
}
