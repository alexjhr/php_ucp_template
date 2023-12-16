<?php

namespace App;

use App\Model\User;
use Delight\Auth\Auth as Authenticator;

class Auth
{
	/**
	 * Saved instance of the authenticator.
	 */
	static $authInstance = null;

	/**
	 * Register an instance of the accounts authenticator.
	 * @param \PDO $pdoConnection The connection to the database through pdo.
	 * @param string $dbPrefix The prefix database.
	 */
	static function createInstance(\PDO $pdoConnection, string $dbPrefix): void
	{
		self::$authInstance = new Authenticator($pdoConnection, null, $dbPrefix, null, 0);
	}

	/**
	 * Obtain the instance of the accounts authenticator.
	 * @return Authenticator
	 */
	static function instance(): Authenticator
	{
		return self::$authInstance;
	}

	/**
	 * Get the user id of the session started.
	 * @return int
	 */
	static function getUserId(): int
	{
		return self::instance()->getUserId();
	}

	/**
	 * Get the user of the session started.
	 * @return User
	 */
	static function getUser()
	{
		$userInstance = new User(self::getUserId());
		return $userInstance;
	}
}
