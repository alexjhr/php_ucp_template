<?php

namespace App\Model;

class Sidebar
{
	/**
	 * Menus list of sidebar.
	 * @var SidebarItem[]
	 */
	static $items = [];

	# Add sidebar item.
	static function addItem(SidebarItem $item)
	{
		array_push(self::$items, $item);
	}

	# Get items.
	static function items()
	{
		usort(Sidebar::$items, function ($a, $b) {
			if ($a->priority == $b->priority) {
				return 0;
			}
			return $a->priority < $b->priority ? -1 : 1;
		});

		$parsedItems = [];
		foreach (self::$items as $item) {
			$parsedItems = array_merge($parsedItems, $item->links);
		}
		return $parsedItems;
	}

	# Verify if the customer is on the current route.
	static function inRoute($route, $actualRoute)
	{
		$isUnique = @$route[2];
		if (is_null(@$route[2])) {
			$isUnique = true;
		}

		$linkRoute = $route[1];
		if ($isUnique) {
			return $linkRoute == $actualRoute;
		}
		return strpos($actualRoute, $linkRoute) !== false;
	}
}
