<?php
// Middlewares/AdminMiddleware.php
namespace Middlewares;

use App\Auth;
use App\Helpers\Redirect;
use Buki\Router\Http\Middleware;
use Delight\Auth\Role;
use Symfony\Component\HttpFoundation\Request;

class AdminMiddleware extends Middleware
{
	public function handle(Request $request)
	{
		# Continue when user is logged & is admin.
		$sessionInstance = Auth::instance();
		if (!$sessionInstance->isLoggedIn()) {
			Redirect::to('/login?to=' . $request->getRequestUri());
			return false;
		}

		# Verify if you have administrative range.
		if (!$sessionInstance->hasRole(Role::ADMIN)) {
			Redirect::to('/');
			return false;
		}
		return true;
	}
}
