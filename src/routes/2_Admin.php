<?php

/*
*   Register a function to export routes.
*   -
*   -
*   function name = lowercase name file + app router
*/

use App\AdminSidebar;

function admin_app_router($router)
{
	$adminMiddleware = ['before' => 'AdminMiddleware'];

	$router->get('/admin', 'AdminController@home', $adminMiddleware);
	$router->get('/admin/view-users', 'AdminUsersController@viewUserList', $adminMiddleware);
	$router->get('/admin/create-user', 'AdminUsersController@viewCreateUser', $adminMiddleware);
	$router->get('/admin/edit-user', 'AdminUsersController@viewEditMe', $adminMiddleware);
	$router->get('/admin/edit-user/:id', 'AdminUsersController@viewEditUser', $adminMiddleware);
	$router->post('/admin/create-user', 'AdminUsersController@postCreateUser', $adminMiddleware);
	$router->post('/admin/edit-user', 'AdminUsersController@postEditMe', $adminMiddleware);
	$router->post('/admin/edit-user/:id', 'AdminUsersController@postEditUser', $adminMiddleware);
	$router->post('/admin/delete-user', 'AdminUsersController@postDeleteUser', $adminMiddleware);

	// Register sidebar items
	AdminSidebar::registerTitle('ADMIN');

	AdminSidebar::registerOne('Administración', 'fas fa-cog', '/admin');

	AdminSidebar::registerSection('Usuarios', 'fas fa-users', [
		['Lista de usuarios', '/admin/view-users'],
		['Crear usuario', '/admin/create-user'],
		['Editar usuario', '/admin/edit-user', false],
	]);
}
