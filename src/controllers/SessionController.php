<?php
// Controllers/SessionController.php
namespace Controllers;

use App\Auth;
use Buki\Router\Http\Controller;
use duncan3dc\Laravel\Blade;
use App\Helpers\Redirect;

class SessionController extends Controller
{
	public function viewLogin()
	{
		return Blade::render('login');
	}

	public function postLogin()
	{
		$email = $_POST['email'];
		$redirectTo = isset($_GET['to']) ? $_GET['to'] : '/';

		try {
			Auth::instance()->login($email, $_POST['password']);
			Redirect::to($redirectTo);
		} catch (\Delight\Auth\InvalidEmailException $e) {
			$GLOBALS['flash']->error('Datos incorrectos');
			Redirect::to("/login?email=$email&to=" . $redirectTo);
		} catch (\Delight\Auth\InvalidPasswordException $e) {
			$GLOBALS['flash']->error('Datos incorrectos');
			Redirect::to("/login?email=$email&to=" . $redirectTo);
		} catch (\Delight\Auth\EmailNotVerifiedException $e) {
			$GLOBALS['flash']->error('El correo electrónico no esta confirmado');
			Redirect::to("/login?email=$email&to=" . $redirectTo);
		} catch (\Delight\Auth\TooManyRequestsException $e) {
			$GLOBALS['flash']->error('Demasiados intentos');
			Redirect::to("/login?email=$email&to=" . $redirectTo);
		}
	}

	public function logout()
	{
		Auth::instance()->logOut();
		Redirect::to('/login');
	}
}
