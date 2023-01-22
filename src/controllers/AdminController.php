<?php
// Controllers/AdminController.php
namespace Controllers;

use Buki\Router\Http\Controller;
use duncan3dc\Laravel\Blade;
use App\Model\User;

class AdminController extends Controller
{
	public function home()
	{
		$userClass = new User($GLOBALS['auth']->getUserId());
		$routes = [['Inicio', '/'], ['Administración', '/admin']];

		$countUsers = User::countByRole(\Delight\Auth\Role::CONSUMER);
		$countAdmins = User::countByRole(\Delight\Auth\Role::ADMIN);

		return Blade::render('admin/home', [
			'user' => $userClass,
			'routes' => $routes,

			'countUsers' => $countUsers,
			'countAdmins' => $countAdmins,
		]);
	}
}
