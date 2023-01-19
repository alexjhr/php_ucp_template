<?php

/*
*   Register a function to export routes.
*   -
*   -
*   function name = lowercase name file + app router
*/

use App\Sidebar;
use Delight\Auth\Role;

function admin_app_router($router)
{
	$adminMiddleware = ['before' => 'AdminMiddleware'];

	$router->get('/admin', 'AdminController@home', $adminMiddleware);
	$router->get('/admin/view-users', 'AdminUsersController@viewUsers', $adminMiddleware);
	$router->get('/admin/create-user', 'AdminUsersController@viewCreateUser', $adminMiddleware);
	$router->get('/admin/edit-user', 'AdminUsersController@viewEditMe', $adminMiddleware);
	$router->get('/admin/edit-user/:id', 'AdminUsersController@viewEditUser', $adminMiddleware);
	$router->post('/admin/create-user', 'AdminUsersController@postCreateUser', $adminMiddleware);
	$router->post('/admin/edit-user', 'AdminUsersController@postEditMe', $adminMiddleware);
	$router->post('/admin/edit-user/:id', 'AdminUsersController@postEditUser', $adminMiddleware);
	$router->post('/admin/delete-user', 'AdminUsersController@postDeleteUser', $adminMiddleware);
	$router->get('/admin/inspect-user/:id', 'AdminUsersController@inspectUser', $adminMiddleware);

	// Register sidebar items
	Sidebar::registerTitle('ADMIN', Role::ADMIN);

	Sidebar::registerOne('Administración', 'fas fa-cog', '/admin', Role::ADMIN);

	Sidebar::registerSection('Usuarios', 'fas fa-users', [
		['Lista de usuarios', '/admin/view-users'],
		['Crear usuario', '/admin/create-user'],
		['Editar usuario', '/admin/edit-user', false],
	], Role::ADMIN);
}
