<?php
// Controllers/AdminUsersController.php
namespace Controllers;

use App\Auth;
use App\RolesDetails;
use Buki\Router\Http\Controller;
use duncan3dc\Laravel\Blade;
use App\Model\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tamtamchik\SimpleFlash\Flash;

class AdminUsersController extends Controller
{
	public function viewUserList()
	{
		$userInstance = Auth::getUser();
		$routes = [
			['Inicio', '/'],
			['Administración', '/admin'],
			['Lista de usuarios', '/admin/view-users']
		];

		return Blade::render('admin/user_list', [
			'user' => $userInstance,
			'routes' => $routes
		]);
	}

	public function viewCreateUser()
	{
		$userInstance = Auth::getUser();
		$routes = [
			['Inicio', '/'],
			['Administración', '/admin'],
			['Crear usuario', '/admin/create-user']
		];
		return Blade::render('admin/user_create', [
			'user' => $userInstance,
			'routes' => $routes
		]);
	}

	public function postCreateUser()
	{
		try {
			$userName = $_POST['username'];
			$userRole = $_POST['role'];

			# The username is required.
			if (!$userName) {
				Flash::error('El nombre de usuario proporcionado no es válido');
				return self::viewCreateUser();
			}

			# Create the user and get your id.
			$adminInterface = Auth::instance()->admin();
			$createdId = $adminInterface->createUserWithUniqueUsername(
				$_POST['email'],
				$_POST['password'],
				$userName
			);

			# Asign role to created user.
			$adminInterface->addRoleForUserById($createdId, RolesDetails::getRoleByName($userRole));

			Flash::success("El usuario \"$userName\" ha sido creado correctamente");
		} catch (\Delight\Auth\InvalidEmailException $e) {
			Flash::error('El correo electrónico proporcionado no es válido');
		} catch (\Delight\Auth\InvalidPasswordException $e) {
			Flash::error('La contraseña proporcionado no es válida');
		} catch (\Delight\Auth\UserAlreadyExistsException $e) {
			Flash::error('El correo electrónico proporcionado ya existe');
		} catch (\Delight\Auth\DuplicateUsernameException $e) {
			Flash::error('El nombre de usuario proporcionado ya existe');
		}
		return self::viewCreateUser();
	}

	public function postDeleteUser(Request $request, Response $response)
	{
		$userId = $_POST['id'];

		try {
			# This admin can delete this user?
			$adminId = Auth::getUserId();

			if (!User::canModifyUser($adminId, $userId)) {
				$response->setStatusCode(Response::HTTP_BAD_REQUEST);
				return $response->sendHeaders();
			}

			# Delete user.
			$adminInterface = Auth::instance()->admin();
			$adminInterface->deleteUserById($userId);

			# If it is the same user, close session.
			if ($adminId == $userId) {
				Auth::instance()->logOut();
			}
			$response->setStatusCode(Response::HTTP_ACCEPTED);
			return $response->sendHeaders();
		} catch (\Delight\Auth\UnknownIdException $e) {
			$response->setStatusCode(Response::HTTP_BAD_REQUEST);
			return $response->sendHeaders();
		}
	}

	public function viewEditMe()
	{
		$userId = Auth::getUserId();
		return self::viewEditUser($userId);
	}

	public function postEditMe()
	{
		$userId = Auth::getUserId();
		return self::postEditUser($userId);
	}

	public function viewEditUser(int $id)
	{
		$userInstance = Auth::getUser();
		$routes = [['Inicio', '/'], ['Administración', '/admin'], ['Editar usuario', '']];

		$editUser = new User($id);
		if ($editUser->exists) {
			array_push($routes, [$editUser->username, '/admin/edit-user/' . $id]);
		} else {
			Flash::error('El usuario no existe');
			array_push($routes, ['Usuario inexistente', '/admin/edit-user/']);
		}

		return Blade::render('admin/user_modify', [
			'user' => $userInstance,
			'routes' => $routes,
			'edit_user' => $editUser
		]);
	}

	public function postEditUser(int $userId)
	{
		# This admin can delete this user?
		$adminId = Auth::getUserId();

		if (!User::canModifyUser($adminId, $userId)) {
			Flash::error('No puedes modificar este usuario');
			return self::viewEditUser($userId);
		}

		if (
			isset($_POST['username']) &&
			isset($_POST['role']) &&
			isset($_POST['email']) &&
			isset($_POST['password'])
		) {
			$userName = $_POST['username'];
			$userRole = $_POST['role'];
			$userEmail = $_POST['email'];

			$userSelected = new User($userId);
			if ($userSelected->exists) {
				# Change username.
				if ($userName != $userSelected->username) {
					if (User::existsByColumnValue('username', $userName)) {
						Flash::error('El nombre de usuario ya esta en uso');
						return self::viewEditUser($userId);
					} else {
						$userSelected->username = $userName;
					}
				}

				# Change email
				if ($userEmail != $userSelected->email) {
					if (User::existsByColumnValue('email', $userEmail)) {
						Flash::error('El correo electrónico ya esta en uso');
						return self::viewEditUser($userId);
					} else {
						$userSelected->email = $userEmail;
					}
				}

				# Update role
				$userSelected->role = RolesDetails::getRoleByName($userRole);

				# Change password
				if ($_POST['password']) {
					$changed = $userSelected->updatePassword($_POST['password']);
					if (!$changed) {
						return self::viewEditUser($userId);
					}
				}
				$userSelected->update();

				Flash::success('Usuario editado correctamente');
			} else {
				Flash::error('Usuario no existente');
			}
		}

		return self::viewEditUser($userId);
	}
}
