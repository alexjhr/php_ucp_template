<?php
// Middlewares/LoggedMiddleware.php
namespace Middlewares;

use App\Auth;
use Buki\Router\Http\Middleware;
use App\Helpers\Redirect;
use Symfony\Component\HttpFoundation\Request;

class LoggedMiddleware extends Middleware
{
	public function handle(Request $request)
	{
		# Continue when user is logged.
		$sessionInstance = Auth::instance();

		if (!$sessionInstance->isLoggedIn()) {
			Redirect::to('/login?to=' . $request->getRequestUri());
			return false;
		}
		return true;
	}
}
