<?php
namespace App\Model;

use App\Auth;
use Delight\Auth\Role;

class User
{
	public int $id;
	public string $username;
	public string $email;
	public $role;
	public string $createdAt;
	public string $lastLogin;
	public bool $exists;

	public function __construct($userData /* User Id or User Data*/)
	{
		$this->exists = false;

		if (!is_array($userData)) {
			$userData = self::existsByColumnValue('id', $userData);
		}

		if (!is_array($userData)) {
			return;
		}
		$this->exists = true;
		$this->id = $userData['id'];
		$this->username = $userData['username'];
		$this->email = $userData['email'];
		$this->role = $userData['roles_mask'];
		$this->createdAt = $userData['registered'];
		$this->lastLogin = $userData['last_login'];
	}

	/**
	 * Update the basic user information.
	 * @return void
	 */
	public function update(): void
	{
		$dbConnection = \MysqliDb::getInstance();
		$dbConnection->where('id', $this->id)->update('users', [
			'username' => $this->username,
			'email' => $this->email,
			'roles_mask' => $this->role,
		]);
	}

	/**
	 * Get the roles that the user has.
	 * @return Array All user roles.
	 */
	public function roles()
	{
		$adminInterface = Auth::instance()->admin();
		return $adminInterface->getRolesForUserById($this->id);
	}

	/**
	 * Verify if a user has a role.
	 * @param int $role The role as one of the constants from the auth consts.
	 * @return bool If you have the role.
	 */
	public function hasRole($role): bool
	{
		# If the user has admin role, he will have access to everything.
		if ($role !== Role::ADMIN && $this->hasRole(Role::ADMIN)) {
			return true;
		}
		$adminInterface = Auth::instance()->admin();
		return $adminInterface->doesUserHaveRole($this->id, $role);
	}

	/**
	 * Update the user password.
	 * @param string $newPassword The new password for the user account.
	 * @return bool If the password change was made.
	 */
	public function updatePassword(string $newPassword): bool
	{
		try {
			$adminInterface = Auth::instance()->admin();
			$adminInterface->changePasswordForUserById($this->id, $newPassword);

			return true;
		} catch (\Delight\Auth\UnknownIdException $e) {
			$GLOBALS['flash']->error('El usuario no es válido');
		} catch (\Delight\Auth\InvalidPasswordException $e) {
			$GLOBALS['flash']->error('La contraseña no es válida');
		}
		return false;
	}

	/**
	 * Obtain all registered users.
	 * @return User[]
	 */
	static function all(): array
	{
		$parsedUsers = [];
		foreach (\MysqliDb::getInstance()->get('users') as $user) {
			array_push($parsedUsers, new self($user));
		}
		return $parsedUsers;
	}

	/**
	 * Verify if there is a user depending on a specific value.
	 * @param string $column The column to verify.
	 * @param string $value The value that the column must have.
	 * @return array|null If there is return array, if not null.
	 */
	static function existsByColumnValue(string $column, string $value)
	{
		$dbConnection = \MysqliDb::getInstance();
		$exists = $dbConnection->where($column, $value)->getOne('users');

		return $exists;
	}

	/**
	 * Count users who have certain specific roles.
	 * @param int $role The role to look for among users.
	 * @return int The number of users.
	 */
	static function countByRole(int $role): int
	{
		$dbConnection = \MysqliDb::getInstance();
		$count = $dbConnection->where('roles_mask', $role)->get('users');

		return $count ? count($count) : 0;
	}

	/**
	 * Can one user modify data from the other?
	 * @param int $userId The modifier user.
	 * @param int $toUserId The user that will be modified.
	 * @param bool If the user has permission to modify it.
	 */
	static function canModifyUser(int $userId, int $toUserId): bool
	{
		$toUser = new self($toUserId);

		if ($toUser) {
			# This admin can delete this user?
			$adminUser = new self($userId);

			if ($userId !== $toUserId) {
				if ($toUser->role == \Delight\Auth\Role::ADMIN) {
					if ($adminUser->createdAt > $toUser->createdAt) {
						return false;
					}
				}
			}
			return true;
		}
		return false;
	}
}
