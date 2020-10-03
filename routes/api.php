<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('registrarUser', 'UserController@registrarUser');
Route::post('loginUser', 'UserController@authenticate');
Route::post('editarUser', 'UserController@editarUser');




// Route::resource('categorias','CategoriaController');
// Route::resource('productos','ProductoController');

Route::resource('peliculas','PeliculaController');



Route::group(['middleware' => ['jwt.verify']], function() {
    /*AÑADE AQUI LAS RUTAS QUE QUIERAS PROTEGER CON JWT*/
});
