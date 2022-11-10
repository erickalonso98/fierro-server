<?php

use Illuminate\Support\Facades\Route;

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

use App\Http\Middleware\AuthMiddleware;


Route::get('/', function () {
    return view('welcome');
});

//rutas de prueba
//Route::get('/welcome/{name?}','App\Http\Controllers\PruebaController@index');
//Route::get('/pruebas','App\Http\Controllers\PruebaController@pruebas');

//rutas de la api rest del proyecto
//usuarios
Route::get('/api/users','App\Http\Controllers\UserController@index');
Route::post('/api/register','App\Http\Controllers\UserController@register');
Route::post('/api/login','App\Http\Controllers\UserController@login');
Route::put('/api/user/update','App\Http\Controllers\UserController@update');
Route::post('/api/user/upload','App\Http\Controllers\UserController@upload')->middleware(AuthMiddleware::class);
Route::get('/api/user/avatar/{filename}','App\Http\Controllers\UserController@getImage');
Route::get('/api/user/profile/{id}','App\Http\Controllers\UserController@profile');
Route::get('/api/user/roles_entry/','App\Http\Controllers\UserController@getUsersRoles');

//Role
Route::resource('/api/role_user','App\Http\Controllers\RoleController');

//personas
Route::resource('/api/person','App\Http\Controllers\PersonController');
Route::post('/api/person/upload','App\Http\Controllers\PersonController@upload');
Route::get('/api/person/image/{filename}','App\Http\Controllers\PersonController@getImage');
Route::get('/api/person/report_ine/{ine}','App\Http\Controllers\PersonController@reportIne');
Route::get('/api/person/report_curp/{curp}','App\Http\Controllers\PersonController@reportCurp');
Route::get('/api/person/report_rfc/{rfc}','App\Http\Controllers\PersonController@reportRfc');
Route::get('/api/person/report_name/{name}','App\Http\Controllers\PersonController@reportName');

//predio 
Route::resource('/api/property','App\Http\Controllers\PropertyController');
Route::post('/api/property/upload','App\Http\Controllers\PropertyController@upload');
Route::get('/api/property/image/{filename}','App\Http\Controllers\PropertyController@getImage');
//Fierro
Route::resource('/api/iron','App\Http\Controllers\IronController');
Route::post('/api/iron/upload','App\Http\Controllers\IronController@upload');
Route::get('/api/iron/image/{filename}','App\Http\Controllers\IronController@getImage');
//Route::get('/api/iron/total/total_high','App\Http\Controllers\IronController@totalHigh');
//Route::get('/api/iron/high/list-high','App\Http\Controllers\IronController@HighIron');
// tipo de tenencia de la tierra
Route::resource('/api/tenencia','App\Http\Controllers\TenenciaController');
//tipo de fierro
Route::resource('/api/type_iron','App\Http\Controllers\TypeController');
//tipo de explotacion de la tierra
Route::resource('/api/exploitation','App\Http\Controllers\ExploitationController');
//estado
Route::resource('/api/state','App\Http\Controllers\StateController');
//municipio
Route::resource('/api/municipalitie','App\Http\Controllers\MunicipalitieController');
//localidad
Route::resource('/api/location','App\Http\Controllers\LocationController');
Route::get('/api/location/change/city_modify','App\Http\Controllers\LocationController@ModifyState');
Route::get('/api/location/states_municipalities/state/{state_id}','App\Http\Controllers\LocationController@getStateMunicipalitie');
Route::get('/api/location/locations_municipalities/{municipalitie_id}','App\Http\Controllers\LocationController@getMunicipalitieLocation');
//alta para registrar fierro
Route::resource('/api/high_iron','App\Http\Controllers\HighController');
Route::get('/api/high_iron/total/total_high/{month}','App\Http\Controllers\HighController@totalHighMonth');
Route::get('/api/high_iron/total/total_highDay/{day}','App\Http\Controllers\HighController@totalHighDay');
Route::get('/api/high_iron/total/total_highYear/{year}','App\Http\Controllers\HighController@totalHighYear');
Route::get('/api/high_iron/total/total_highDate/{date}','App\Http\Controllers\HighController@totalHighDate');
//Baja de fierro
Route::resource('/api/low_iron','App\Http\Controllers\LowController');
Route::get('/api/low_iron/low_total/total_lowDate/{date}','App\Http\Controllers\LowController@totalLowDate');
Route::get('/api/low_iron/low_total/total_lowMonth/{month}','App\Http\Controllers\LowController@totalLowMonth');
Route::get('/api/low_iron/low_total/total_lowDay/{day}','App\Http\Controllers\LowController@totalLowDay');
Route::get('/api/low_iron/low_total/total_lowYear/{year}','App\Http\Controllers\LowController@totalLowYear');
//Busqueda de fierro
Route::resource('/api/search_iron','App\Http\Controllers\SearchController');
Route::get('/api/search_iron/search_total/total_searchDate/{date}','App\Http\Controllers\SearchController@totalSearchDate');
Route::get('/api/search_iron/search_total/total_searchMonth/{month}','App\Http\Controllers\SearchController@totalSearchMonth');
Route::get('/api/search_iron/search_total/total_searchDay/{day}','App\Http\Controllers\SearchController@totalSearchDay');
Route::get('/api/search_iron/search_total/total_searchYear/{year}','App\Http\Controllers\SearchController@totalSearchYear');
//revalidacion de fierro
Route::resource('/api/revalidation_iron','App\Http\Controllers\RevalidationController');
Route::get('/api/revalidation_iron/revalidation_total/total_revalidationDate/{date}','App\Http\Controllers\RevalidationController@totalRevalidationDate');
Route::get('/api/revalidation_iron/revalidation_total/total_revalidationMonth/{month}','App\Http\Controllers\RevalidationController@totalRevalidationMonth');
Route::get('/api/revalidation_iron/revalidation_total/total_revalidationDay/{day}','App\Http\Controllers\RevalidationController@totalRevalidationDay');
Route::get('/api/revalidation_iron/revalidation_total/total_revalidationYear/{year}','App\Http\Controllers\RevalidationController@totalRevalidationYear');