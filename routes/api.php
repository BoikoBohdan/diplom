<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'API'], static function () {

    // POST method for the all single login
    Route::post('login', 'AuthController@login')->name('login');

    // POST method for sending email with generated password
    Route::post('password-forgot', 'ApiForgotPasswordController@sendNewPasswordEmail');

    // POST method for confirmation invite member
    Route::post('invite-confirmation', 'UserController');

    Route::group(['middleware' => ['api', 'jwt.auth']], static function () {

        // GET method for the all single logout
        Route::get('logout', 'AuthController@logout');

        // ! ADMIN PANEL API ROUTES

        Route::group(['prefix' => 'admin/', 'namespace' => 'Admin', 'middleware' => ['check_role:master_admin|super_admin|admin']], static function () {

            // Routes group only for Master Admins and Super Admins
            Route::group(['middleware' => ['check_role:master_admin|super_admin|admin']], static function () {

                // Company manage resource
                Route::resource('companies', 'CompanyController', [
                    'except' => ['create', 'show', 'edit']
                ]);
            });

            // Export single action
            Route::get('export', 'ExportController');

            // Invite master admins, super admins, admins, drivers single action
            Route::post('invite', 'InviteController');

            // Auth User
            Route::get('auth/user', 'UserController@getAuthUser')->name('auth.user');

            // Mass Update Users
            Route::put('users/bulk-update', 'UserController@bulkUpdate');

            // Mass Delete Users
            Route::delete('users/bulk-delete', 'UserController@bulkDelete');

            // User resource
            Route::resource('users', 'UserController', [
                'except' => ['store', 'show', 'create']
            ]);

            // User work locations
            Route::resource('work-locations', 'UsersWorkLocationsController', [
                'only' => ['store', 'edit', 'update', 'destroy']
            ]);

            // Role resource
            Route::resource('roles', 'RoleController', [
                'only' => ['index']
            ]);

            // Mass update drivers
            Route::put('drivers/bulk-update', 'DriverController@bulkUpdate');

            // Mass delete drivers
            Route::delete('drivers/bulk-delete', 'DriverController@bulkDestroy');

            // Drivers Resource
            Route::resource('drivers', 'DriverController', [
                'except' => ['show', 'create']
            ]);

            // Add driver document
            Route::post('drivers/{user}/documents', 'Documents\DriverDocumentsController@setDocument')->name('drivers.documents');

            // Update driver document
            Route::patch('drivers/{user}/documents/{document}', 'Documents\DriverDocumentsController@updateDocument')->name('drivers.documents.update');

            // Add vehicle document
            Route::post('vehicles/{vehicle}/documents', 'Documents\VehicleDocumentsController@setDocument');

            // Update vehicle document
            Route::put('vehicles/{vehicle}/documents/{document}', 'Documents\VehicleDocumentsController@updateDocument');

            // Delete document
            Route::delete('documents/{document}', 'Documents\DocumentsController@destroy');

            // Drivers vehicles
            Route::resource('vehicles', 'DriverVehiclesController', [
                'except' => ['show', 'create']
            ]);

            // Vehicle Types list
            Route::get('vehicle-types', 'VehicleTypeController@index');

            // Orders statuses
            Route::get('orders/statuses', 'OrdersController@getStatuses')->name('orders.statuses');

            // Set order status
            Route::patch('orders/{order}/set-status', 'OrdersController@setStatus');

            // Orders resource
            Route::resource('orders', 'OrdersController', [
                'except' => ['create', 'delete']
            ]);

            // Cancel order
            Route::patch('orders/{order}/cancel', 'OrdersController@cancel');

            // Detach order`s drivers
            Route::delete('orders/{order}/remove-drivers', 'OrdersController@detachDrivers');

            // Order cancel reasons
            Route::get('cancel-reasons', 'CancelReasonsController@index');

            //application statistics
            Route::get('statistics', 'StatisticsController');

            // Gods eye
            Route::group(['prefix' => 'gods-eye/'], static function () {

                // Get  orders
                Route::get('orders', 'GodsEyeController@getGodsEyeOrders')->name('godsEye.orders');

                // Get drivers
                Route::get('drivers', 'GodsEyeController@getGodsEyeDrivers')->name('godsEye.drivers');

                // Assign driver for order
                Route::post('assign-drivers', 'GodsEyeController@assignDriversToOrder')->name('godsEye.assignDrivers');

                //Get drivers orders
                Route::get('driver/{user}/orders', 'GodsEyeController@getDriversOrders')->name('godsEye.DriversOrders');

                Route::post('driver/{user}/set-waypoints', 'GodsEyeController@setDriverWaypoints');

            });

            // Wallet
            Route::resource('wallets', 'WalletsController', [
                'only' => ['update']
            ]);

            // Shifts resource
            Route::resource('shifts', 'ShiftsController', [
                'except' => ['show']
            ]);

             // Cities resource
             Route::resource('cities', 'CitiesController', [
                'only' => ['index', 'store', 'destroy']
            ]);
        });

        // ! DRIVER API ROUTES

        Route::group(['prefix' => 'driver/', 'namespace' => 'Driver'], static function () {

            // Orders
            Route::resource('order', 'OrderController');

            // Set order status
            Route::patch('order/{order}/set-status', 'OrderController@setStatus');

            // Driver details
            Route::get('details', 'DriverController@show');

            //Set coordinates for driver
            Route::post('set-coordinates', 'DriverController@setCoordinates');

            // Ratings
            Route::post('set-score', 'ScoreController@setScore');

            // Set vehicle status
            Route::patch('vehicles/{vehicle}/set-status', 'VehicleController@setStatus');

            // Set work location status
            Route::patch('worklocations/{location}/set-active', 'DriverController@setActiveWorkLocation');

            // Set is_shift status
            Route::patch('set-is-shift-status', 'DriverController@setShiftStatus');

            // Get shifts for driver
            Route::get('shifts', 'ShiftsController@getShifts');

            // Assign driver on shifts
            Route::patch('shifts/{shift}', 'ShiftsController@setDriverOnShifts');

            // Set order cansel reason request
            Route::post('order/{order}/cancel-request', 'OrderController@setCancelOrderReason');
        });

        // !! CHAT API ROUTES

        Route::group(['prefix' => 'chat', 'namespace' => 'Chat'], static function () {

            // Create caht room
            Route::post('create-room', 'ChatController@createChatRoom');

            // Chat user list
            Route::get('user-list', 'ChatController@getUsers');

            // Chat room list
            Route::get('room-list', 'ChatController@getRooms');

            // Get message history
            Route::get('history/{room}', 'ChatController@getMessageHistory');

            // Create message
            Route::post('create-message', 'ChatController@storeMessage');

            // Create message with file
            Route::post('create-message-with-file', 'ChatController@storeFile');

        });
    });
});
