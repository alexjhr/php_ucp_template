<?php
use App\Model\Sidebar;
use App\Model\SidebarItem;

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

	# Add admin menu to sidebar.
	$adminSidebar = new SidebarItem(9999);
	$adminSidebar->addTitle('ADMIN');
	$adminSidebar->addOneLink('Administración', 'fas fa-cog', '/admin');
	$adminSidebar->addMultipleLinks('Usuarios', 'fas fa-users', [
		['Lista de usuarios', '/admin/view-users'],
		['Crear usuario', '/admin/create-user'],
		['Editar usuario', '/admin/edit-user', false],
	]);
	Sidebar::addItem($adminSidebar);
}
