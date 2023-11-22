<?php

use App\Actions\Appointment\CreateAppointment;
use App\Actions\Appointment\DeleteAppointment;
use App\Actions\Appointment\ListAppointments;
use App\Actions\Appointment\Records\CreateAppointmentRecord;
use App\Actions\Appointment\Records\ListAppointmentRecords;
use App\Actions\Auth\RefreshToken;
use App\Actions\Auth\UserLogin;
use App\Actions\Auth\UserLogout;
use App\Actions\Auth\UserSession;
use App\Actions\Car\CreateCar;
use App\Actions\Car\DeleteCar;
use App\Actions\Car\EditCar;
use App\Actions\Car\GetCarByVin;
use App\Actions\Car\ListCars;
use App\Actions\Car\ShowCar;
use App\Actions\Record\EditRecord;
use App\Actions\Record\ShowRecord;
use App\Actions\Service\Appointment\CompleteServiceAppointment;
use App\Actions\Service\Appointment\CreateServiceAppointment;
use App\Actions\Service\Appointment\CreateServiceAppointmentRecord;
use App\Actions\Service\Appointment\DeleteServiceAppointmentRecord;
use App\Actions\Service\Appointment\EditServiceAppointmentRecord;
use App\Actions\Service\Car\ListServiceCars;
use App\Actions\Service\Car\ShowServiceCar;
use App\Actions\Service\CreateService;
use App\Actions\Service\DeleteService;
use App\Actions\Service\EditService;
use App\Actions\Service\ListServices;
use App\Actions\Service\ShowService;
use App\Actions\User\Car\ConfirmUserCarAppointment;
use App\Actions\User\Car\CreateUserCar;
use App\Actions\User\Car\DeleteUserCar;
use App\Actions\User\Car\EditUserCar;
use App\Actions\User\Car\ListUserCars;
use App\Actions\User\Car\ShowUserCar;
use App\Actions\User\Car\ShowUserCarRecords;
use App\Actions\User\CreateUser;
use App\Actions\User\DeleteUser;
use App\Actions\User\EditUser;
use App\Actions\User\ListUsers;
use App\Actions\User\ShowUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => '/auth', 'middleware' => 'api'], static function() {
    Route::post('/login', UserLogin::class)->name('login');
    Route::post('/refresh', RefreshToken::class)->name('refresh');

});

Route::group(['prefix' => '/auth', 'middleware' => ['api', 'auth:api']], static function() {
    Route::get('/user', UserSession::class);
    Route::post('/logout', UserLogout::class)->name('logout');
});

Route::group(['middleware' => ['api', 'auth:api']], static function() {
    Route::group(['prefix' => 'services'], static function () {
        Route::get('/', ListServices::class);
        Route::post('/', CreateService::class);
        Route::put('/{service}', EditService::class);
        Route::get('/{service}', ShowService::class);
        Route::delete('/{service}', DeleteService::class);
        Route::group(['prefix' => '{service}/cars'], static function () {
            Route::get('/', ListServiceCars::class);
            Route::get('/{car}', ShowServiceCar::class);
        });
        Route::group(['prefix' => '{service}/appointments'], static function () {
            Route::post('/', CreateServiceAppointment::class);
            Route::post('/{appointment}/complete', CompleteServiceAppointment::class);
            Route::post('/{appointment}/records', CreateServiceAppointmentRecord::class);
            Route::put('/{appointment}/records/{record}', EditServiceAppointmentRecord::class);
            Route::delete('/{appointment}/records/{record}', DeleteServiceAppointmentRecord::class);
        });
    });

    Route::group(['prefix' => 'cars'], static function () {
        Route::get('/', ListCars::class);
        Route::post('/', CreateCar::class);
        Route::get('/vin/{vin}', GetCarByVin::class);
        Route::put('/{car}', EditCar::class);
        Route::get('/{car}', ShowCar::class);
        Route::delete('/{car}', DeleteCar::class);
    });

    Route::group(['prefix' => 'appointments'], static function () {
        Route::get('/', ListAppointments::class);
        Route::post('/', CreateAppointment::class);
        Route::put('/{appointment}', EditRecord::class);
        Route::get('/{appointment}', ShowRecord::class);
        Route::delete('/{appointment}', DeleteAppointment::class);
        Route::group(['prefix' => '{appointment}/records'], static function () {
            Route::get('/', ListAppointmentRecords::class);
            Route::post('/', CreateAppointmentRecord::class);
        });
    });

    Route::group(['prefix' => 'users'], static function () {
        Route::get('/', ListUsers::class);
        Route::post('/', CreateUser::class);
        Route::put('/{user}', EditUser::class);
        Route::get('/{user}', ShowUser::class);
        Route::delete('/{user}', DeleteUser::class);
        Route::group(['prefix' => '{user}/cars'], static function () {
            Route::get('/', ListUserCars::class);
            Route::post('/', CreateUserCar::class);
            Route::get('/{car}', ShowUserCar::class);
            Route::put('/{car}', EditUserCar::class);
            Route::delete('/{car}', DeleteUserCar::class);
            Route::get('/{car}/records', ShowUserCarRecords::class);
            Route::post('/{car}/appointments/{appointment}', ConfirmUserCarAppointment::class);
        });

    });
});

