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

//CAMBIA VISTA LOGIN COMO INICIO DE SITIO
Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');

// Agregado para el correcto cierre de sesion
Route::get('/logout', 'Auth\LoginController@logout');

//RUTAS DE USUARIO LARAVEL
Auth::routes();

//RUTA DE VISTA, UNA VEZ QUE SE ESTA LOGUEADO
 Route::get('/home', 'HomeController@index');

//RUTAS ADMINISTRACION DE USUARIOS
Route::resource('users','UsersController');

//RUTAS ASIGNAR ROLES
Route::get('users/asignRole/{user}', 'UsersController@asignRole');
Route::post('users/saveRole', 'UsersController@saveRole');

//RUTAS ASIGNAR ESTABLECIMIENTOS
Route::get('users/asignEstab/{user}', 'UsersController@asignEstab');
Route::post('users/saveEstab', 'UsersController@saveEstab');

//RUTAS ASIGNAR ESPECIALIDAD
Route::get('users/asignEspec/{user}', 'UsersController@asignEspec');
Route::post('users/saveEspec', 'UsersController@saveEspec');

//RUTA GENERA EXCEL DE USUARIOS
Route::get('users/reporte/excel', 'UsersController@excel');

//RUTA PARA EL CAMBIO DE PASSWORD
Route::get('users/password/cambiar', 'PasswordUsersController@password');
Route::post('users/password/cambiar', 'PasswordUsersController@save');

//RUTAS PACIENTES
Route::resource('pacientes','PacientesController');
//creacion de pacientes desde la pantalla de contrarreferencia y lista de espera
Route::get('crear/pacientes/{flujo}', 'PacientesController@create2')->middleware('pacientes');
//edicion de pacientes para usuario con rol pacientesFull
Route::get('editar/pacientes/su/{id}/{flujo}', 'PacientesController@editSU')->middleware('pacientesfull');
//edicion de pacientes desde la pantalla de lista de espera
Route::get('editar/pacientes/di/{id}/{flujo}', 'PacientesController@editDI')->middleware('pacientes');

//alerta de creacion de pacientes si no hay rol asignado
Route::get('/alertaPacientes', function () {
    return view('alertaPacientes');
});

//RUTAS PARA CONTRARREFERENCIA
Route::get('contrarreferencias/create','ContrarreferenciasController@create')->middleware('hospital'); //Creacion de Contrarreferencia
Route::post('contrarreferencias','ContrarreferenciasController@store')->middleware('hospital'); //Guardado de Contrarreferencia
Route::get('contrarreferencias/le/{id}','ContrarreferenciasController@listaEspera')->middleware('hospital'); //Historial LE
Route::get('contrarreferencias/pdf/{id}','ContrarreferenciasController@pdf'); //Muestra documento PDF de Contrarreferencia

Route::get('contrarreferencias/some','ContrarreferenciasController@some')->middleware('some'); //Revisión de Contrarreferencias
Route::get('contrarreferencias/{id}/agendaSome','ContrarreferenciasController@agendaSome')->middleware('some'); //Resumen Contrarreferencia SOME
Route::post('contrarreferencias/actualizar','ContrarreferenciasController@actualizaSome')->middleware('some'); //Actualización de Contrarreferencia

Route::get('contrarreferencias/aps','ContrarreferenciasController@aps')->middleware('aps'); //Revisión de Contrarreferencias
Route::get('contrarreferencias/{id}/detalleAps','ContrarreferenciasController@detalleAps')->middleware('aps'); //Resumen Contrarreferencia APS
Route::get('contrarreferencias/{id}/cerrar','ContrarreferenciasController@cierraAps')->middleware('aps'); //Cierre de Contrarreferencia

Route::get('contrarreferencias/reporte','ContrarreferenciasController@reporte'); //Filtro Reporte Contrarreferencia
Route::get('contrarreferencias/resultado','ContrarreferenciasController@resultado'); //Resultado Reporte Contrarreferencia
Route::get('contrarreferencias/{id}/detalle','ContrarreferenciasController@detalle'); //Resumen Contrarreferencia
Route::get('contrarreferencias/excel','ContrarreferenciasController@excel');//Exporta a excel


//RUTA LOGIN AJAX
Route::get('getEstab/{mail}','Auth\LoginController@getEstab');
Route::get('getEspec/{mail}','Auth\LoginController@getEspec');

//RUTA AUTOCOMPLETA PACIENTE
Route::get('getPaciente',array('as'=>'getPaciente','uses'=>'ContrarreferenciasController@autoComplete'));

//RUTA AUTOCOMPLETA DIAGNOSTICO
Route::get('getDiagnostico',array('as'=>'getDiagnostico','uses'=>'ContrarreferenciasController@autoComplete2'));


route::get('/consultarDB/{id}', 'ConsultasController@getPacienteDB')->name('consultas.db');
route::get('/consultarFon/{id}', 'ConsultasController@getPacienteFon')->name('consultas.fon');