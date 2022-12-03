<?php
// Middlewares/AdminMiddleware.php
namespace Middlewares;

use Buki\Router\Http\Middleware;
use App\Helpers\Redirect;
use Symfony\Component\HttpFoundation\Request;

class AdminMiddleware extends Middleware
{
	public function handle(Request $request)
	{
		// Continue when user is logged & is admin.
		$actualUser = $GLOBALS['auth'];
		if (!$actualUser->isLoggedIn()) {
			Redirect::to('/login?to=' . $request->getRequestUri());
			return false;
		}

		// Check role admin
		if (!$actualUser->hasRole(\Delight\Auth\Role::ADMIN)) {
			Redirect::to('/');
			return false;
		}
		return true;
	}
}
