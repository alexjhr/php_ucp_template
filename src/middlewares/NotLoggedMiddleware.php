<?php
// Middlewares/NotLoggedMiddleware.php
namespace Middlewares;

use Buki\Router\Http\Middleware;
use App\Helpers\Redirect;

class NotLoggedMiddleware extends Middleware
{
	public function handle()
	{
		// Continue when user is not logged.
		if ($GLOBALS['auth']->isLoggedIn()) {
			Redirect::to('/');
			return false;
		}
		return true;
	}
}
