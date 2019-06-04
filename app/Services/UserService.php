<?php

namespace App\Services;

use App\Http\Resources\Members\UserResource;
use App\Role;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserService extends BasicService
{
    /**
     * @param $request
     * @param Model $model
     * @return \Illuminate\Database\Eloquent\Collection|Model[]|\Illuminate\Http\JsonResponse
     */
    public function all ($request, Model $model)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $company_id = null;
        switch ($user->getRole()) {
            case 'master_admin':
                $roles_array = ['master_admin', 'super_admin', 'admin'];
                break;
            case 'super_admin':
                $roles_array = ['super_admin', 'admin'];
                break;
            case 'admin':
                $roles_array = ['admin'];
                $company_id = $user->company_id;
                break;
            default:
                $roles_array = [];
                break;
        }

        $sortedRaw = $this->getSortedRawMemberList($request->input('sort', 'id'), $request->input('order', 'desc'));

        $roles = Role::whereIn('name', $roles_array)->pluck('id');
        $users = User::whereIn('role_id', $roles)
            ->where('users.id', '<>', $user->id)
            ->where(static function ($query) {
                return $query->whereNull('invite_token')
                    ->orWhereNotNull('invite_token_verified_at');
            })
            ->when(isset($company_id), static function ($query) use ($company_id) {
                return $query->where('company_id', $company_id);
            })
            ->when($request->filter, static function ($query) use ($request) {
                if (!$request->name || $request->name == 'all') {
                    return $query->whereRaw("CONCAT(first_name, ' ',last_name) LIKE '%{$request->filter}%'")
                        ->orWhere('email', 'LIKE', "%{$request->filter}%")
                        ->orWhere('phone', 'LIKE', "%{$request->filter}%")
                        ->orWhereHas('company', static function ($q) use ($request) {
                            $q->where('name', 'LIKE', "%{$request->filter}%");
                        });
                } elseif ($request->name == 'full_name') {
                    return $query->whereRaw("CONCAT(first_name, ' ',last_name) LIKE %{$request->filter}%");
                } elseif ($request->name == 'company') {
                    return $query->whereHas('company', static function ($q) use ($request) {
                        $q->where('name', 'LIKE', "%{$request->filter}%");
                    });
                } else {
                    return $query->where($request->name, 'LIKE', "%{$request->filter}%");
                }
            })
            ->join('companies', static function ($join) {
                $join->on('users.company_id', '=', 'companies.id');
            })
            ->with(['role', 'company'])
            ->orderByRaw($sortedRaw)
            ->paginate($request->input('perPage', 10));

        $resource = UserResource::collection($users);

        return response()->json($resource->collection)->withHeaders([
            'X-Total-Count' => $resource->resource->toArray()['total'],
            'Access-Control-Expose-Headers' => 'X-Total-Count'
        ]);
    }

    /**
     * @param Model $model
     * @param array $attributes
     * @return mixed|void
     */
    public function store (Model $model, array $attributes)
    {
        $model->create($attributes);

        $model->assignRole('admin');
    }

    /**
     * @param Model $model
     * @return UserResource|Model
     */
    public function edit (Model $model)
    {
        return new UserResource($model);
    }

    /**
     * @param Model $model
     * @param array $attributes
     */
    public function bulkUpdate (Model $model, array $attributes)
    {
        $model->whereIn('id', $attributes['data'])
            ->update(['status' => $attributes['status']]);
    }

    /**
     * @param Model $model
     * @param array $attributes
     */
    public function bulkDelete (Model $model, array $attributes)
    {
        $model->bulkDestroy($attributes);
    }

    private function getSortedRawMemberList ($sort, $order)
    {
        $ordered = [
            'sort' => [
                'full_name' => 'CONCAT(users.first_name, " ", users.last_name)',
                'email' => 'email',
                'company' => 'companies.name',
                'phone' => 'phone',
                'id' => 'users.id'
            ],
            'order' => [
                'asc' => 'ASC',
                'desc' => 'DESC'
            ]
        ];

        $sortedRaw = !isset($ordered['sort'][$sort]) ? $ordered['sort']['id'] : $ordered['sort'][$sort];
        $order = strtolower($order);
        $sortedRaw .= ' ' . (isset($ordered['order'][$order]) ? $ordered['order'][$order] : $ordered['order']['desc']);

        return $sortedRaw;
    }

}
