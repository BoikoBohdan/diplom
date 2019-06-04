<?php

namespace App;

use App\Components\Classes\StoreFile\File;
use App\Components\Traits\Filters\UsersFilter;
use App\Components\Traits\Permissions\UserPermissions;
use App\Components\Traits\Roles\RolesManipulator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use RolesManipulator;
    use SoftDeletes;
    use UserPermissions;
    use UsersFilter;

    public const INACTIVE_STATUS = 0;

    /**
     * Default value of items per page
     *
     * @var integer
     */
    protected $perPage = 10;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'phone',
        'image',
        'status',
        'address',
        'role_id',
        'password',
        'last_name',
        'first_name',
        'company_id',
        'invite_token',
        'invite_token_verified_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @var array
     */
    protected $appends = [
        'full_name'
    ];

    /**
     * @var array
     */
    protected $dates = [
        'deleted_at'
    ];

    /**
     * @return BelongsTo
     */
    public function role ()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * @return BelongsTo
     */
    public function company ()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * @return HasOne
     */
    public function driver ()
    {
        return $this->hasOne(Driver::class);
    }

    /**
     * Get related work locations
     *
     * @return HasMany
     */
    public function workLocations ()
    {
        return $this->hasMany(UsersWorkLocations::class);
    }

    /**
     * Related messages
     *
     * @return HasMany
     */
    public function sendedMessages ()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Related messages
     *
     * @return HasMany
     */
    public function recievedMessages ()
    {
        return $this->hasMany(Message::class, 'reciever_id');
    }

    /**
     * Related rooms
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function rooms ()
    {
        return $this->belongsToMany(ChatRoom::class);
    }

    /**
     * Get full name attribute
     *
     * @return string
     */
    public function getFullNameAttribute ()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * @return mixed
     */
    public function getCoordinatesAttribute ()
    {
        return UsersWorkLocations::isActive($this->workLocations)->getCoordinates();
    }

    /**
     * @param Builder $query
     * @param int $role
     * @return Builder
     */
    public function scopeByRole (Builder $query, int $role)
    {
        return $query->where('role_id', $role);
    }

    /**
     * @param $query
     * @param int $company
     * @return Builder
     */
    public function scopeByCompany (Builder $query, $company)
    {
        return $this->byCompany($query, $company);
    }

    /**
     * @param $query
     * @param int $role
     * @param int $company
     * @return Builder
     */
    public function scopeChatUsers (Builder $query, int $role, $company)
    {
        return $query->byRole($role)->byCompany($company)->select('id', 'first_name', 'last_name', 'image');
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier ()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims ()
    {
        return [];
    }

    /**
     * Get full name
     *
     * @return string
     */
    public function getFullName (): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Create new User
     *
     * @param array $params
     * @return User
     * @throws \Illuminate\Validation\ValidationException
     */
    public function add (array $params)
    {
        if (isset($params['image'])) {
            $file = new File($params['image']);
            $file->validation(['jpg', 'png', 'svg']);
            $params['image'] = $file->store('driver_photos');
        }

        return self::create($params);
    }

    /**
     * Update User
     *
     * @param array $params
     * @return bool
     * @throws \Illuminate\Validation\ValidationException
     */
    public function change (array $params)
    {
        if (isset($params['image'])) {
            $file = new File($params['image']);
            $file->validation(['jpg', 'png', 'svg']);
            $params['image'] = $file->store('driver_photos');
        }
        $this->update($params);
    }

    /**
     * Delete a list of Users
     *
     * @param array $params
     * @return bool
     */
    public function bulkDestroy (array $params)
    {
        $this->whereIn('id', $params)->each(static function (User $item) {
            $item->delete();
        });
    }

    /**
     * Update a list of Users
     *
     * @param array $params
     * @return bool
     */
    public function bulkUpdate (array $params)
    {
        $this->whereIn('id', $params['data'])->update(['status' => $params['status']]);
    }

    /**
     * Check if user is able to join chat room
     *
     * @param ChatRoom $room
     * @return bool
     */
    public function canJoinRoom ($room): bool
    {
        $room = ChatRoom::find($room);

        return $room->users->contains(function ($user) {
            return $user->id === $this->id;
        });
    }

    /**
     * Check if user is some Admin
     *
     * @return bool
     */
    public function isSomeAdmin (): bool
    {
        switch ($this->role->name) {
            case 'admin':
                return true;
                break;
            case 'super_admin':
                return true;
                break;
            case 'master_admin':
                return true;
                break;
            default:
                return false;
        }
    }
}
