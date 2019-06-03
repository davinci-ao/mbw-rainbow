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
Auth::routes(['verify' => true]);


Route::get('/', function () {
    return view('pages.welcome');
});

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('/projects', 'ProjectsController');
Route::get('/projects/view/{id}', 'ProjectsController@show');

Route::get('/studentAdding/{id}', 'ProjectsController@studentProjectAdding');
Route::get('/project/{id}/addstudents', 'ProjectsController@addStudentsToProject');
Route::get('/project/{project}/addstudent/{student}', 'ProjectsController@addStudent');

Route::resource('/groups', 'GroupsController');
Route::get('/groups/view/{id}', 'GroupsController@show');

Route::resource('/users', 'UsersController');

Route::resource('/students', 'StudentsController');
Route::get('/students/view/{id}', 'StudentsController@show');

Route::get('/teachers', 'UsersController@teacher');
Route::get('/clients', 'UsersController@client');




