<?php
namespace App;

use App\Model\User;

class Sidebar
{
	static function items(User $user)
	{
		$items = [['Inicio', 'fas fa-tachometer-alt', '/']];

		if ($user->hasRole(\Delight\Auth\Role::ADMIN)) {
			$items = array_merge($items, self::admin());
		}

		return $items;
	}

	static function admin()
	{
		return [
			'ADMIN',
			['Administración', 'fas fa-cog', '/admin'],
			[
				'Usuarios',
				'fas fa-users',
				[
					['Lista de usuarios', '/admin/view-users', true],
					['Crear usuario', '/admin/create-user', true],
					['Editar usuario', '/admin/edit-user', false],
				],
			],
		];
	}

	static function actualRoute($route, $actualRoute)
	{
		$routeUrl = $route[1];
		$routeIsUnique = $route[2];

		if ($routeIsUnique) {
			return $routeUrl == $actualRoute;
		}
		return strpos($actualRoute, $routeUrl) !== false;
	}
}
