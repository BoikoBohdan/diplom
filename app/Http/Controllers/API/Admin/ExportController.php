<?php

namespace App\Http\Controllers\API\Admin;

use App\Components\Export\Contract\Export;
use App\Driver;
use App\Http\Controllers\Controller;
use App\Order;
use App\Role;
use App\Shift;
use App\UsersWorkLocations;
use App\Vehicle;
use App\VehicleType;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use JWTAuth;

class ExportController extends Controller
{
    public $export;

    public function __construct (Export $export)
    {
        $this->export = $export;
    }

    public function __invoke (Request $request)
    {
        $this->validate($request, ['export' => 'required|string']);

        $method = 'export' . ucfirst($request->export);
        if (!method_exists($this, $method)) {
            return response()->json(['message' => 'export_method_not_found'], 404);
        }

        return $this->$method();
    }

    public function exportAdmins ()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $user_role = $user->getRole();

        $company_id = null;
        switch ($user_role) {
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

        $roles = Role::query()->whereIn('name', $roles_array)->pluck('id');

        $admins = User::query()
            ->whereIn('role_id', $roles)
            ->where(static function ($query) {
                return $query->whereNull('invite_token')
                    ->orWhereNotNull('invite_token_verified_at');
            })
            ->when(isset($company_id), static function ($q) use ($company_id) {
                return $q->where('company_id', $company_id);
            })
            ->join('companies', static function ($q) {
                $q->on('users.company_id', '=', 'companies.id');
            })
            ->join('roles', static function ($q) {
                $q->on('users.role_id', '=', 'roles.id');
            })
            ->get([
                DB::raw('CONCAT(users.first_name, " ", users.last_name) as full_name'),
                'email',
                DB::raw('roles.display_name as role_name'),
                DB::raw('companies.name as company_name'),
                'phone',
                'status'
            ])->toArray();

        $columns = ['Full Name', 'E-mail', 'User Type', 'Company Relation', 'Phone', 'Status'];

        $fname = $this->export->setColumnNames($columns)->setData($admins)->setFileName('admins')->generate();

        return response()->download(storage_path('app/export/') . $fname);
    }

    public function exportDrivers ()
    {
        $request = new Request();

        $drivers = Driver::indexAll($request)->get();

        $exported = $drivers->map(static function (Driver $driver) {
            $vehicle = Vehicle::isActive($driver->vehicles);
            $location = UsersWorkLocations::isActive($driver->user->workLocations);

            return [
                'full_name' => $driver->user->getFullName(),
                'phone' => $driver->phone,
                'assigned_orders' => $driver->orders->map(function ($order) {
                    return $order->reference_id;
                }),
                'wallet' => (string)$driver->wallet->amount,
                'status' => array_flip(Driver::SHIFT_STATUSES)[$driver->status],
                'current_vehicle' => $vehicle ? VehicleType::LIST_TYPES[$vehicle->vehicle_type_id] : '',
                'current_city' => $location->city->name ?? '',
                'stops' => (string)$driver->countStops()
            ];
        })->toArray();

        $columns = ['Full Name', 'Phone', 'Assigned Orders', 'Wallet', 'Status', 'Current Vehicle', 'Current City', 'Stops'];

        $fname = $this->export->setColumnNames($columns)->setData($exported)->setFileName('drivers')->generate();

        return response()->download(storage_path('app/export/') . $fname);
    }

    public function exportOrders ()
    {
        $request = new Request();

        $orders = Order::getAll($request)->get();

        $columns = [
            'ID', 'Reference', 'Pickup Date', 'Pickup Time From', 'Dropoff Time From', 'Restaurant Postcode', 'Restaurant Name',
            'Dropoff Postcode', 'Order Fee', 'Driver Name', 'Status'
        ];

        $exported = $orders->map(function (Order $order) {
            $pickup = $order->pickup_location;
            $dropoff = $order->dropoff_location;
            return [
                'id' => $order->id,
                'reference_id' => $order->reference_id,
                'pickup_date' => $order->pickup_date,
                'pickup_time_from' => $order->pickup_time_from,
                'dropoff_time_from' => $order->dropoff_time_from,
                'restaurant_name' => $order->restaurant->name,
                'restaurant_postcode' => $order->restaurant->postcode,
                'dropoff_postcode' => isset($dropoff->postcode) ? $dropoff->postcode : $order->postcode,
                'fee' => $order->fee,
                'driver_name' => $order->getDriversList(),
                'status' => Order::STATUS_TITLES[$order->status],
            ];
        })->toArray();

        $fname = $this->export->setColumnNames($columns)->setData($exported)->setFileName('orders')->generate();

        return response()->download(storage_path('app/export/') . $fname);
    }

    public function exportShifts ()
    {
        $columns = ['Date', 'City', 'Start', 'End', 'Meal'];
        $shifts = Shift::get();
        foreach ($shifts as $shift) {
            $result[] = [
                'Date' => $shift->date,
                'City' => $shift->city->name,
                'Start' => $shift->start,
                'End' => $shift->end,
                'Meal' => $shift::MEALS_TITLES[$shift->meal]
            ];
        }

        $fname = $this->export->setColumnNames($columns)->setData($result)->setFileName('shifts')->generate();

        return response()->download(storage_path('app/export/') . $fname);
    }
}
