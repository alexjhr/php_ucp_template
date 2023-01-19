<?php

namespace App;

use App\Model\User;
use \Delight\Auth\Role;

class Sidebar
{
	static $SIDEBAR_ITEMS = array();

	/*
	* Register title to next items sidebar.
	*/
	static function registerTitle($title, $role)
	{
		if (!User::logged()->hasRole($role)) return;

		array_push(Sidebar::$SIDEBAR_ITEMS, $title);
	}

	/*
	* Register one item to sidebar.
	*/
	static function registerOne($name, $icon, $route, $role)
	{
		if (!User::logged()->hasRole($role)) return;

		array_push(Sidebar::$SIDEBAR_ITEMS, [$name, $icon, $route]);
	}

	/*
	* Register section to sidebar.
	*/
	static function registerSection($name, $icon, $items, $role)
	{
		if (!User::logged()->hasRole($role)) return;

		array_push(Sidebar::$SIDEBAR_ITEMS, [$name, $icon, $items]);
	}

	/*
	* Obtain all items of sidebar
	*/
	static function items()
	{
		return Sidebar::$SIDEBAR_ITEMS;
	}

	static function inRoute($route, $actual_route)
	{
		$isUnique = @$route[2];
		if (is_null(@$route[2])) $isUnique = true;

		$url = $route[1];

		if ($isUnique) {
			return $url == $actual_route;
		}
		return strpos($actual_route, $url) !== false;
	}
}
