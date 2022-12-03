<?php
namespace App\Model;

class User
{
	public $id;
	public $username;
	public $email;
	public $created_at;
	public $last_login;

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
		$this->created_at = $userData['registered'];
		$this->last_login = $userData['last_login'];
	}

	public function update()
	{
		$db = \MysqliDb::getInstance();

		$db->where('id', $this->id)->update('panel_users', [
			'username' => $this->username,
			'email' => $this->email,
			'roles_mask' => $this->role,
		]);
	}

	public function hasRole($role)
	{
		return $GLOBALS['auth']->admin()->doesUserHaveRole($this->id, $role);
	}

	public function getRoles()
	{
		return $GLOBALS['auth']->admin()->getRolesForUserById($this->id);
	}

	public function updatePassword($newPassword)
	{
		try {
			$adminInterface = $GLOBALS['auth']->admin();
			$adminInterface->changePasswordForUserById($this->id, $newPassword);

			return true;
		} catch (\Delight\Auth\UnknownIdException $e) {
			$GLOBALS['flash']->error('El usuario no es válido');
		} catch (\Delight\Auth\InvalidPasswordException $e) {
			$GLOBALS['flash']->error('La contraseña no es válida');
		}
		return false;
	}

	static function all()
	{
		$parsed_users = [];
		foreach (\MysqliDb::getInstance()->get('panel_users') as $user) {
			array_push($parsed_users, new self($user));
		}
		return $parsed_users;
	}

	static function existsByColumnValue($column, $value)
	{
		$db = \MysqliDb::getInstance();
		$exists = $db->where($column, $value)->getOne('panel_users');

		return $exists;
	}

	static function countByRole($role)
	{
		$db = \MysqliDb::getInstance();
		$result = $db->where('roles_mask', $role)->get('panel_users');

		return $result ? count($result) : 0;
	}

	static function canModifyUser($userId, $toUserId)
	{
		$toUser = new self($toUserId);

		if ($toUser) {
			// This admin can delete this user?
			$adminUser = new self($userId);

			if ($userId !== $toUserId) {
				if ($toUser->role == \Delight\Auth\Role::ADMIN) {
					if ($adminUser->created_at > $toUser->created_at) {
						return false;
					}
				}
			}
			return true;
		}
		return false;
	}
}
