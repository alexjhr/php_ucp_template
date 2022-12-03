<?php
namespace App;

class RolesDetails
{
	const info = [
		'CONSUMER' => ['Usuario', 'secondary'],
		'ADMIN' => ['Administrador', 'danger'],
	];

	static function availableRoles()
	{
		return array_keys(RolesDetails::info);
	}

	static function getRoleByName($role)
	{
		// Search index of role name
		$userRoleIndex = array_search($role, \Delight\Auth\Role::getNames());
		return \Delight\Auth\Role::getValues()[$userRoleIndex];
	}
}
