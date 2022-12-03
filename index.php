<?php
// Load dependencies
require 'vendor/autoload.php';

use duncan3dc\Laravel\Blade;
use duncan3dc\Laravel\BladeInstance;
use duncan3dc\Laravel\Directives;
use Tamtamchik\SimpleFlash\Flash;
use Tamtamchik\SimpleFlash\TemplateFactory;
use Tamtamchik\SimpleFlash\Templates;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// Load app dependencies
require 'src/app/Sidebar.php';
require 'src/app/RolesDetails.php';

// Load helpers
require 'src/helpers/Redirect.php';
require 'src/helpers/DateFormat.php';
require 'src/helpers/HttpClient.php';

// Load models
require 'src/models/User.php';

// Config render views
$public = (new Directives())->withCss('/public')->withJs('/public');
$blade = new BladeInstance(
	__DIR__ . '/views',
	__DIR__ . '/views/cache',
	$public,
);

Blade::setInstance($blade);

// Check if SSL enabled
if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
	$protocol =
		$_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' ? 'https://' : 'http://';
} else {
	$protocol =
		!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off'
			? 'https://'
			: 'http://';
}

// Define SITE_URL
$site_url =
	$protocol .
	$_SERVER['HTTP_HOST'] .
	(dirname($_SERVER['SCRIPT_NAME']) == DIRECTORY_SEPARATOR ? '' : '/') .
	trim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
define('SITE_URL', $site_url);

// Load environment variables.
$dotenv = Dotenv\Dotenv::createImmutable('./');
$dotenv->load();

try {
	// Connect to database (MySQL)
	$db_host = $_ENV['DB_HOST'];
	$db_name = $_ENV['DB_NAME'];
	$db_user = $_ENV['DB_USER'];
	$db_password = $_ENV['DB_PASS'];
	$db_connection = new PDO( // (Auth connection)
		"mysql:host=$db_host;dbname=$db_name",
		$db_user,
		$db_password,
	);
	$db_mysqli = new mysqli($db_host, $db_user, $db_password, $db_name);
	new MysqliDb($db_mysqli); // (App connection)
} catch (Exception $e) {
	echo Blade::render('errors/exception', ['e' => $e]);
	exit();
}

// Initialize Auth
$GLOBALS['auth'] = new \Delight\Auth\Auth(
	$db_connection,
	null,
	'panel_',
	null,
	0,
);

// Initialize flash alerts
$template = TemplateFactory::create(Templates::BOOTSTRAP);
$GLOBALS['flash'] = new Flash($template);

// Initialize Router
$router = new \Buki\Router\Router([
	'paths' => [
		'controllers' => 'src/controllers',
		'middlewares' => 'src/middlewares',
	],
	'namespaces' => [
		'controllers' => 'Controllers',
		'middlewares' => 'Middlewares',
	],
]);

// Defining routes
$router->get('/login', 'HomeController@viewLogin', [
	'before' => 'NotLoggedMiddleware',
]);
$router->post('/login', 'HomeController@postLogin', [
	'before' => 'NotLoggedMiddleware',
]);

$router->get('logout', 'HomeController@logout', [
	'before' => 'LoggedMiddleware',
]);

$router->get('/', 'HomeController@viewHome', [
	'before' => 'LoggedMiddleware',
]);

$router->group(
	'/admin',
	function ($r) {
		$r->get('/', 'AdminController@home');

		$r->get('/view-users', 'AdminUsersController@viewUsers');
		$r->get('/create-user', 'AdminUsersController@viewCreateUser');
		$r->get('/edit-user', 'AdminUsersController@viewEditMe');
		$r->get('/edit-user/:id', 'AdminUsersController@viewEditUser');
		$r->post('/create-user', 'AdminUsersController@postCreateUser');
		$r->post('/edit-user', 'AdminUsersController@postEditMe');
		$r->post('/edit-user/:id', 'AdminUsersController@postEditUser');
		$r->post('/delete-user', 'AdminUsersController@postDeleteUser');
		$r->get('/inspect-user/:id', 'AdminUsersController@inspectUser');
	},
	['before' => 'AdminMiddleware'],
);

$router->error(function (Request $request, Response $response, Exception $e) {
	$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);

	return Blade::render('errors/exception', ['e' => $e]);
});

// Run router
$router->run();
