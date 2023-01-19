<?php
// Controllers/HomeController.php
namespace Controllers;

use Buki\Router\Http\Controller;
use duncan3dc\Laravel\Blade;
use App\Helpers\Redirect;
use App\Model\User;

class HomeController extends Controller
{
	public function viewHome()
	{
		$userClass = new User($GLOBALS['auth']->getUserId());
		$sidebar = \App\Sidebar::items();
		$routes = [['Inicio', '/'], ['Pagina de Inicio', '/']];

		return Blade::render('user_home', [
			'user' => $userClass,
			'routes' => $routes,
			'sidebar' => $sidebar,
		]);
	}

	public function viewLogin()
	{
		return Blade::render('login');
	}
	public function postLogin()
	{
		$email = $_POST['email'];

		try {
			$GLOBALS['auth']->login($email, $_POST['password']);

			$redirect = isset($_GET['to']) ? $_GET['to'] : '/';
			Redirect::to($redirect);
		} catch (\Delight\Auth\InvalidEmailException $e) {
			$GLOBALS['flash']->error('Datos incorrectos');
			Redirect::to("/login?email=$email");
		} catch (\Delight\Auth\InvalidPasswordException $e) {
			$GLOBALS['flash']->error('Datos incorrectos');
			Redirect::to("/login?email=$email");
		} catch (\Delight\Auth\EmailNotVerifiedException $e) {
			$GLOBALS['flash']->error(
				'El correo electrónico no esta confirmado',
			);
			Redirect::to("/login?email=$email");
		} catch (\Delight\Auth\TooManyRequestsException $e) {
			$GLOBALS['flash']->error('Demasiados intentos');
			Redirect::to("/login?email=$email");
		}
	}
	public function logout()
	{
		$actualUser = $GLOBALS['auth'];
		$actualUser->logOut();

		Redirect::to('/login');
	}
}
