<?php
namespace App\Helpers;

class Redirect
{
	static function to($path)
	{
		header("Location: $path");
		exit();
	}
}
