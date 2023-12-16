<?php

namespace App\Model;

class SidebarItem
{
	public int $priority; # Item priority
	public array $links;

	public function __construct($priority = 1)
	{
		$this->links = [];
		$this->priority = $priority;
	}

	public function addOneLink($name, $icon, $linkTo): void
	{
		array_push($this->links, [$name, $icon, $linkTo]);
	}

	public function addTitle($title): void
	{
		array_push($this->links, $title);
	}

	public function addMultipleLinks($name, $icon, $links): void
	{
		$this->addOneLink($name, $icon, $links);
	}
}
