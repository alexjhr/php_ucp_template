<?php

/*
*   Register a function to export routes.
*   -
*   -
*   function name = lowercase name file + app router
*/

use App\Sidebar;
use Delight\Auth\Role;

function login_app_router($router)
{
	$router->get('/', 'HomeController@viewHome', ['before' => 'LoggedMiddleware']);

	$router->get('/login', 'HomeController@viewLogin', ['before' => 'NotLoggedMiddleware']);
	$router->post('/login', 'HomeController@postLogin', ['before' => 'NotLoggedMiddleware']);

	$router->get('logout', 'HomeController@logout', ['before' => 'LoggedMiddleware']);

	// Register sidebar items
	Sidebar::registerOne('Inicio', 'fas fa-tachometer-alt', '/', Role::CONSUMER);
}
