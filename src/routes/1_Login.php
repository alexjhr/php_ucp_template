<?php

/*
*   Register a function to export routes.
*   -
*   -
*   function name = lowercase name file + app router
*/

function login_app_router($router)
{
	$router->get('/login', 'SessionController@viewLogin', ['before' => 'NotLoggedMiddleware']);
	$router->post('/login', 'SessionController@postLogin', ['before' => 'NotLoggedMiddleware']);

	$router->get('logout', 'SessionController@logout', ['before' => 'LoggedMiddleware']);
}
