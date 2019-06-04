<?php

namespace App\Services;

use App\{Components\Traits\Cachable,
    Components\Traits\OrderTrait,
    Http\Resources\Orders\Details,
    Http\Resources\Orders\Edit,
    Http\Resources\Orders\OrderCollection,
    Jobs\API\Orders\SetOrderStatus,
    Order};
use Illuminate\{Database\Eloquent\Model, Support\Facades\Cache};

class OrderService extends BasicService
{
    use OrderTrait;
    use Cachable;

    /**
     * @param $request
     * @param Model $model
     * @return OrderCollection|\Illuminate\Database\Eloquent\Collection|Model[]
     */
    public function all ($request, Model $model)
    {
        return new OrderCollection(
            $model->getAll($request)->paginate($request->input('perPage', $model->perPage))
        );
    }

    /**
     * @param Model $model
     * @param array $attributes
     * @return mixed|void
     */
    public function store (Model $model, array $attributes)
    {
        $model->add($this->transformOrder(collect($attributes)));
    }

    /**
     * @param Model $model
     * @return Details|Model
     */
    public function show (Model $model)
    {
        return new Details($model);
    }

    /**
     * @param Model $model
     * @return Edit|Model
     */
    public function edit (Model $model)
    {
        return new Edit($model);
    }

    /**
     * @param Model $model
     * @param array $attributes
     * @return bool|void
     */
    public function update (Model $model, array $attributes)
    {
        $location = $model->locations()->byType($attributes['type'])->first();

        $location->update([
            'streetaddress' => $attributes['streetaddress']
        ]);
    }

    /**
     * @param Model $model
     * @return bool|void|null
     * @throws \Exception
     */
    public function destroy (Model $model)
    {
        $model->delete();
    }

    /**
     * @param Order $model
     */
    public function detachDrivers (Order $model)
    {
        $model->detachDrivers();

        dispatch_now(new SetOrderStatus($model, Order::STATUSES['not_assigned']));
    }

    /**
     * @param Order $model
     */
    public function getStatuses (Order $model)
    {
        !Cache::has('order-statuses')
            ? $this->setForeverCacheValue('order-statuses', $model->statuses)
            : false;
    }

    /**
     * @param Order $model
     * @param array $attributes
     */
    public function cancel (Order $model, array $attributes)
    {
        dispatch_now(new SetOrderStatus($model, Order::STATUSES['cancelled']));

        $model->cancelReason()->update($attributes);
    }

    /**
     * @param Order $model
     * @param int $status
     */
    public function setStatus (Order $model, int $status)
    {
        dispatch_now(new SetOrderStatus($model, $status));
    }
}
