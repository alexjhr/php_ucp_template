<?php
// Middlewares/NotLoggedMiddleware.php
namespace Middlewares;

use App\Auth;
use Buki\Router\Http\Middleware;
use App\Helpers\Redirect;

class NotLoggedMiddleware extends Middleware
{
	public function handle()
	{
		# Continue when user is not logged.
		$sessionInstance = Auth::instance();

		if ($sessionInstance->isLoggedIn()) {
			Redirect::to('/');
			return false;
		}
		return true;
	}
}
