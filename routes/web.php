<?php

use Illuminate\Support\Facades\Route;


Route::get("create","UserController@create")->name("user.create");
Route::post("create","UserController@postCreate")->name("user.postCreate");

Route::get("/","UserController@demo")->name("user.demo");
Route::get("login","UserController@login")->name("user.login");
Route::post("post-login","UserController@postLogin")->name("user.postLogin");
Route::post("demo/ajax-search","UserController@search")->name("user.search");

Route::group(
    ['middleware' => ['authlogin'] ],
    function(){
        Route::get("make-word","UserController@makeWord")->name("user.makeWord");
        Route::post("create-word","UserController@createWord")->name("user.createWord");
        Route::get("load-word","UserController@loadWord")->name("user.loadWord");
        Route::get("logout","UserController@logout")->name("user.logout");
        Route::post("deleteWord","UserController@deleteWord")->name("user.deleteWord");
    }
);
