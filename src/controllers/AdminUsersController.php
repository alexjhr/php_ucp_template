<?php
// Controllers/AdminUsersController.php
namespace Controllers;

use App\Helpers\Redirect;
use App\RolesDetails;
use Buki\Router\Http\Controller;
use duncan3dc\Laravel\Blade;
use App\Model\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminUsersController extends Controller
{
	public function viewUserList()
	{
		$userClass = new User($GLOBALS['auth']->getUserId());
		$routes = [
			['Inicio', '/'],
			['Administración', '/admin'],
			['Lista de usuarios', '/admin/view-users'],
		];

		return Blade::render('admin/user_list', [
			'user' => $userClass,
			'routes' => $routes,
		]);
	}

	public function viewCreateUser()
	{
		$userClass = new User($GLOBALS['auth']->getUserId());
		$routes = [
			['Inicio', '/'],
			['Administración', '/admin'],
			['Crear usuario', '/admin/create-user'],
		];
		return Blade::render('admin/user_create', [
			'user' => $userClass,
			'routes' => $routes,
		]);
	}

	public function postCreateUser()
	{
		try {
			$userName = $_POST['username'];
			$userRole = $_POST['role'];

			// userName is required.
			if (!$userName) {
				$GLOBALS['flash']->error(
					'El nombre de usuario proporcionado no es válido',
				);
				return self::viewCreateUser();
			}

			// Create user and obtain id.
			$userId = $GLOBALS['auth']
				->admin()
				->createUserWithUniqueUsername(
					$_POST['email'],
					$_POST['password'],
					$userName,
				);

			// Asign role to created user.
			$GLOBALS['auth']
				->admin()
				->addRoleForUserById(
					$userId,
					RolesDetails::getRoleByName($userRole),
				);

			$GLOBALS['flash']->success(
				"El usuario \"$userName\" ha sido creado correctamente",
			);
		} catch (\Delight\Auth\InvalidEmailException $e) {
			$GLOBALS['flash']->error(
				'El correo electrónico proporcionado no es válido',
			);
		} catch (\Delight\Auth\InvalidPasswordException $e) {
			$GLOBALS['flash']->error(
				'La contraseña proporcionado no es válida',
			);
		} catch (\Delight\Auth\UserAlreadyExistsException $e) {
			$GLOBALS['flash']->error(
				'El correo electrónico proporcionado ya existe',
			);
		} catch (\Delight\Auth\DuplicateUsernameException $e) {
			$GLOBALS['flash']->error(
				'El nombre de usuario proporcionado ya existe',
			);
		}
		return self::viewCreateUser();
	}

	public function postDeleteUser(Request $request, Response $response)
	{
		$userId = $_POST['id'];

		try {
			// This admin can delete this user?
			$adminId = $GLOBALS['auth']->getUserId();

			$result = User::canModifyUser($adminId, $userId);
			if (!$result) {
				$response->setStatusCode(Response::HTTP_BAD_REQUEST);
				return $response->sendHeaders();
			}

			// Delete user.
			$GLOBALS['auth']->admin()->deleteUserById($userId);

			// Same user, logout.
			if ($adminId == $userId) {
				$GLOBALS['auth']->logOut();
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
		return self::viewEditUser($GLOBALS['auth']->getUserId());
	}

	public function postEditMe()
	{
		return self::postEditUser($GLOBALS['auth']->getUserId());
	}

	public function viewEditUser($id)
	{
		$userClass = new User($GLOBALS['auth']->getUserId());
		$routes = [
			['Inicio', '/'],
			['Administración', '/admin'],
			['Editar usuario', ''],
		];

		$editUser = new User($id);
		if ($editUser->exists) {
			array_push($routes, [
				$editUser->username,
				'/admin/edit-user/' . $id,
			]);
		} else {
			$GLOBALS['flash']->error('El usuario no existe');
			array_push($routes, ['Usuario inexistente', '/admin/edit-user/']);
		}

		return Blade::render('admin/user_modify', [
			'user' => $userClass,
			'routes' => $routes,
			'edit_user' => $editUser,
		]);
	}

	public function postEditUser($userId)
	{
		// This admin can delete this user?
		$adminId = $GLOBALS['auth']->getUserId();

		$result = User::canModifyUser($adminId, $userId);
		if (!$result) {
			$GLOBALS['flash']->error('No puedes modificar este usuario');
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
				// Change username
				if ($userName != $userSelected->username) {
					if (User::existsByColumnValue('username', $userName)) {
						$GLOBALS['flash']->error(
							'El nombre de usuario ya esta en uso',
						);
						return self::viewEditUser($userId);
					} else {
						$userSelected->username = $userName;
					}
				}

				// Change email
				if ($userEmail != $userSelected->email) {
					if (User::existsByColumnValue('email', $userEmail)) {
						$GLOBALS['flash']->error(
							'El correo electrónico ya esta en uso',
						);
						return self::viewEditUser($userId);
					} else {
						$userSelected->email = $userEmail;
					}
				}

				// Update role
				$userSelected->role = RolesDetails::getRoleByName($userRole);

				// Change password
				if ($_POST['password']) {
					$changed = $userSelected->updatePassword(
						$_POST['password'],
					);
					if (!$changed) {
						return self::viewEditUser($userId);
					}
				}
				// Update values (username, email)
				$userSelected->update();

				$GLOBALS['flash']->success('Usuario editado correctamente');
			} else {
				$GLOBALS['flash']->error('Usuario no existente');
			}
		}

		return self::viewEditUser($userId);
	}
}
