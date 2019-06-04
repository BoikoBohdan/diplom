<?php

namespace App\Http\Controllers\API\Admin;

use App\{Components\Classes\StoreFile\File,
    Http\Controllers\Controller,
    Http\Requests\API\BulkActions\BulkDestroy,
    Http\Resources\Members\UserResource,
    Services\UserService,
    User};
use Illuminate\{Http\JsonResponse, Http\Request, Http\Response, Validation\ValidationException};
use Storage;

class UserController extends Controller
{
    /**
     * @var UserService
     */
    public $service;

    /**
     * UserController constructor.
     * @param UserService $service
     */
    public function __construct (UserService $service)
    {
        $this->service = $service;

        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Display a listing of the resource.
     * Check role for auth user and select only users for auth user
     * Don't select which has not verified invite token
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model[]|JsonResponse
     */
    public function index (Request $request, User $user)
    {
        return $this->service->all($request, $user);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function store (Request $request, User $user)
    {
        $this->validate($request, [
            'first_name' => 'required|string|min:2|max:255',
            'last_name' => 'required|string|min:2|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|max:100',
        ]);

        $request->merge(['password' => bcrypt($request->password)]);

        $this->service->store($user, $request->all());

        return $this->isSuccess();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return UserResource|\Illuminate\Database\Eloquent\Model
     */
    public function edit (User $user)
    {
        return $this->service->edit($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     * @throws ValidationException
     */
    public function update (Request $request, User $user)
    {
        $rules = [
            'first_name' => 'required|string|min:2|max:255',
            'last_name' => 'required|string|min:2|max:255',
            'email' => "required|email|unique:users,email,{$user->id},id",
            'address' => 'required|string|max:255',
            'phone' => 'required|string|regex:/^\+[0-9]{12}$/',
            'company_id' => 'required|integer|exists:companies,id',
            'status' => 'required|boolean',
            'role_id' => 'required|integer|exists:roles,id'
        ];

        $data = $request->all();
        $validation = [];
        $update = [];

        foreach ($data as $key => $value) {
            if ($key == 'image') {
                $file = new File($request->image);
                $file->validation(['jpg', 'png']);
                $update[$key] = $file->store('user_photos');
                continue;
            }

            if (array_key_exists($key, $rules) === false) {
                continue;
            }

            $validation[$key] = $rules[$key];
            $update[$key] = $value;
        }

        $this->validate($request, $validation);

        $this->service->update($user, $update);

        return $this->isSuccess();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy (User $user)
    {
        $this->service->destroy($user);

        return $this->isSuccess();
    }

    /**
     * Resources bulk update in storage
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function bulkUpdate (Request $request, User $user)
    {
        $this->validate($request, [
            'data' => 'required|array',
            'data.*' => 'integer',
            'status' => 'required|boolean'
        ]);

        $this->service->bulkUpdate($user, $request->only(['status', 'data']));

        return $this->isSuccess();
    }

    /**
     * @param BulkDestroy $request
     * @param User $users
     * @return JsonResponse
     */
    public function bulkDelete (BulkDestroy $request, User $users)
    {
        $this->service->bulkDelete($users, $request->all());

        return $this->isSuccess();
    }

    /**
     * @return UserResource
     */
    public function getAuthUser ()
    {
        return new UserResource(auth()->user());
    }
}
