<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/', 'HomeController@index')->name('home');

    // Rutas de usuarios
    Route::get('/users', 'UsersController@all')->name('allusers');
    Route::get('/me', 'UsersController@me')->name('me');
    Route::get('/user/delete/{id}', 'UsersController@delete')->name('deleteuser');

    // Rutas para Vacaciones
    Route::get('/holidays', 'HolidaysController@me')->name('holidays');
    Route::get('/holidays/new', 'HolidaysController@new')->name('holidays.new');
    Route::get('/holidays/edit/{id}', 'HolidaysController@edit')->name('holidays.edit');
    Route::post('/holidays/create', 'HolidaysController@register')->name('holidays.create');
    Route::post('/holidays/upgrade', 'HolidaysController@upgrade')->name('holidays.upgrade');
    Route::get('/holidays/review', 'HolidaysController@review')->name('holidays.review');
    Route::get('/holidays/review/{id}', 'HolidaysController@single')->name('holidays.reviewSingle');
    Route::post('/holidays/review/{id}/approve', 'HolidaysController@approve')->name('holidays.approve');
    Route::post('/holidays/review/{id}/modificate', 'HolidaysController@modificate')->name('holidays.modificate');
    Route::post('/holidays/review/{id}/reject', 'HolidaysController@reject')->name('holidays.reject');
    Route::get('/admin/holidays', 'HolidaysController@adminHolidays')->name('holidays.admin');

    // Rutas para Teletrabajo
    Route::get('/remote', 'RemoteController@me')->name('remote');
    Route::get('/remote/new', 'RemoteController@new')->name('remote.new');
    Route::get('/remote/edit/{id}', 'RemoteController@edit')->name('remote.edit');
    Route::post('/remote/create', 'RemoteController@register')->name('remote.create');
    Route::post('/remote/upgrade', 'RemoteController@upgrade')->name('remote.upgrade');
    Route::get('/remote/review', 'RemoteController@review')->name('remote.review');
    Route::get('/remote/review/{id}', 'RemoteController@single')->name('remote.reviewSingle');
    Route::get('/remote/review/{id}/approve', 'RemoteController@approve')->name('remote.approve');
    Route::post('/remote/review/{id}/modificate', 'RemoteController@modificate')->name('remote.modificate');
    Route::post('/remote/review/{id}/reject', 'RemoteController@reject')->name('remote.reject');

    // Rutas para Ausencias
    Route::get('/absences', 'AbsencesController@me')->name('absences');
    Route::get('/absences/new', 'AbsencesController@new')->name('absences.new');
    Route::get('/absences/edit/{id}', 'AbsencesController@edit')->name('absences.edit');
    Route::post('/absences/create', 'AbsencesController@register')->name('absences.create');
    Route::post('/absences/upgrade', 'AbsencesController@upgrade')->name('absences.upgrade');
    Route::get('/absences/review', 'AbsencesController@review')->name('absences.review');
    Route::get('/admin/absences', 'AbsencesController@adminHolidays')->name('absences.admin');

    // Rutas notificaciones
    Route::get('/notifications', 'NotificationsController@me')->name('notifications');

    // Rutas fichaje
    Route::get('/sign', 'SignController@index')->name('sign');
    Route::get('/sign/rest', 'SignController@rest')->name('sign.rest');
    Route::get('/sign/unrest', 'SignController@unrest')->name('sign.unrest');
    Route::post('/sign/in', 'SignController@in')->name('sign.in');
    Route::get('/sign/out', 'SignController@out')->name('sign.out');
    Route::get('/sign/today', 'SignController@today')->name('sign.today');
    Route::get('/sign/edit/{day}', 'SignController@edit')->name('sign.edit');
    Route::post('/sign/store', 'SignController@store')->name('sign.store');
    Route::get('/sign/day/{init}/{end}', 'SignController@day')->name('sign.day');
    Route::get('/admin/signs/today', 'SignController@seeToday')->name('sign.seeToday');
    Route::get('/admin/signs/day/{init}/{end}', 'SignController@seeDay')->name('sign.seeDay');
});

Route::get('/clear-cache', function () {
    $code = Artisan::call('cache:clear');
    Artisan::call('config:clear');
    return 'cache cleared';
});

Route::get('/fullcalendar', 'FullCalendarController@index');
