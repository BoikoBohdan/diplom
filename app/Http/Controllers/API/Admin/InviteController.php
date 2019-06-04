<?php /** @noinspection ALL */

namespace App\Http\Controllers\API\Admin;

use App\{Company, Http\Controllers\Controller, Notifications\InviteMember, User};
use Illuminate\{Http\Request, Validation\ValidationException};
use JWTAuth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class InviteController extends Controller
{
    /**
     * Main rules for validation request
     *
     * @var array
     */
    private $generalRules = [
        'full_name' => 'required|string|max:200',
        'email' => 'required|email|unique:users,email',
    ];

    /**
     * Permissions for send invite
     *
     * @var array
     */
    private $permissions = [
        'master_admin' => ['master_admin', 'super_admin', 'admin', 'driver'],
        'super_admin' => ['super_admin', 'admin', 'driver'],
        'admin' => ['admin', 'driver'],
    ];

    /**
     * @var User|null $authUser
     */
    private $authUser;

    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws NotFoundHttpException
     */
    public function __invoke (Request $request)
    {
        $action = 'create_' . strtolower($request->role);
        $this->authUser = JWTAuth::parseToken()->authenticate();

        if (!method_exists($this, $action)) {
            throw new NotFoundHttpException('Role not found.');
        }

        if (!$this->checkPermissions($request->role, $this->authUser->getRole())) {
            return response()->json(['message' => 'permission_denied'], 401);
        }

        $token = str_random(60);
        $user = $this->$action($request, $token);

        try {
            $user->notify(new InviteMember($user, $this->authUser));
        } catch (\Swift_TransportException $e) {
            return response()->json(['message' => 'failed_sent_invitation']);
        }

        $user->save();

        if ($user->role->name === 'driver') {
            $user->driver()->create();
        }

        return response()->json(['message' => 'success']);
    }

    /**
     * Check permissions for send invite
     *
     * @param string $inviteRole
     * @param string $authRole
     * @return bool
     */
    private function checkPermissions (string $inviteRole, string $authRole): bool
    {
        if (array_key_exists($authRole, $this->permissions) === false) {
            return false;
        }

        if (array_search($inviteRole, $this->permissions[$authRole]) === false) {
            return false;
        }

        return true;
    }

    /**
     * Create Master Admin User
     *
     * @param Request $request
     * @param string $token
     * @return User
     * @throws ValidationException
     */
    private function create_master_admin (Request $request, string $token): User
    {
        $rules = $this->generalRules;
        $this->requestValidation($request, $rules);

        $transformFullName = $this->fullNameTransform($request->full_name);

        $company = Company::where('name', Company::GENERAL_COMPANY)->first();
        $request->merge([
            'first_name' => $transformFullName['first_name'],
            'last_name' => $transformFullName['last_name'],
            'status' => false,
            'invite_token' => $token,
            'company_id' => null
        ]);

        $user = new User($request->all());
        $user->prepareAssignRole('master_admin');

        return $user;
    }

    /**
     * Request validate
     *
     * @param Request $request
     * @param array $rules
     * @throws ValidationException
     */
    private function requestValidation (Request $request, array $rules)
    {
        $this->validate($request, $rules);
    }

    /**
     * Transform full name to first name and last name
     *
     * @param string $fullName
     * @return array
     */
    private function fullNameTransform (string $fullName): array
    {
        $transform = ['first_name' => '', 'last_name' => ''];
        $fullNameEx = explode(' ', $fullName);

        if (count($fullNameEx) == 1) {
            $transform['first_name'] = trim($fullNameEx[0]);
        } else {
            $transform['last_name'] = $fullNameEx[count($fullNameEx) - 1];
            unset($fullNameEx[count($fullNameEx) - 1]);
            $transform['first_name'] = trim(implode(' ', $fullNameEx));
        }

        return $transform;
    }

    /**
     * Create Super Admin User
     *
     * @param Request $request
     * @param string $token
     * @return User
     * @throws ValidationException
     */
    private function create_super_admin (Request $request, string $token): User
    {
        $rules = $this->generalRules;
        $this->requestValidation($request, $rules);

        $transformFullName = $this->fullNameTransform($request->full_name);

        $company = Company::where('name', Company::GENERAL_COMPANY)->first();
        $request->merge([
            'first_name' => $transformFullName['first_name'],
            'last_name' => $transformFullName['last_name'],
            'status' => false,
            'invite_token' => $token,
            'company_id' => !empty($company) ? $company->id : 0
        ]);

        $user = new User($request->all());
        $user->prepareAssignRole('super_admin');

        return $user;
    }

    /**
     * Create Admin User
     *
     * @param Request $request
     * @param string $token
     * @return User
     * @throws ValidationException
     */
    private function create_admin (Request $request, string $token): User
    {
        $rules = $this->generalRules;
        $authRole = $this->authUser->getRole();

        if ($authRole != 'admin') {
            $rules['company'] = 'required|integer|exists:companies,id';
        }
        $this->requestValidation($request, $rules);

        $transformFullName = $this->fullNameTransform($request->full_name);

        if ($authRole == 'admin') {
            $company = $this->authUser->company;
        } else {
            $company = Company::findOrFail($request->company);
        }

        $request->merge([
            'first_name' => $transformFullName['first_name'],
            'last_name' => $transformFullName['last_name'],
            'status' => false,
            'company_id' => $company->id,
            'invite_token' => $token
        ]);

        $user = new User($request->all());
        $user->prepareAssignRole('admin');

        return $user;
    }

    private function create_driver (Request $request, string $token): User
    {
        $rules = $this->generalRules;
        $authRole = $this->authUser->getRole();

        $this->requestValidation($request, $rules);

        $transformFullName = $this->fullNameTransform($request->full_name);

        if ($authRole == 'admin') {
            $company = $this->authUser->company;
        } else {
            $company = Company::findOrFail($request->company);
        }

        $request->merge([
            'first_name' => $transformFullName['first_name'],
            'last_name' => $transformFullName['last_name'],
            'status' => false,
            'company_id' => $company->id,
            'invite_token' => $token
        ]);

        $user = new User($request->all());
        $user->prepareAssignRole('driver');

        return $user;
    }
}
