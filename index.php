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
use Delight\Auth\Auth;

function register_all_files($match)
{
	foreach (glob($match) as $route) {
		require $route;
	}
	return null;
}

// Load environment variables.
$dotenv = Dotenv\Dotenv::createImmutable('./');
$dotenv->load();

// Load app dependencies
register_all_files('src/app/*.php');

// Load helpers
register_all_files('src/helpers/*.php');

// Load all models
register_all_files('src/models/*.php');

// Config template engine 
$public = (new Directives())->withCss('/public')->withJs('/public');
Blade::setInstance(new BladeInstance(
	__DIR__ . '/views',
	__DIR__ . '/cache',
	$public,
));

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

	$GLOBALS['auth'] = new Auth($db_connection, null, $_ENV['DB_PREFIX'], null, 0);
} catch (Exception $e) {
	echo Blade::render('error/exception', ['e' => $e]);
	exit;
}

// Initialize flash alerts
$template = TemplateFactory::create(Templates::BOOTSTRAP);
$GLOBALS['flash'] = new Flash($template);

// Initialize router & load all routes
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
foreach (glob('src/routes/*.php') as $route) {
	require $route;

	$functionName = @array_pop(explode('/', $route));
	$functionName = @array_pop(explode('_', $route));
	$functionName = str_replace('.php', '', $functionName);
	$functionName = strtolower($functionName);
	$functionName .= '_app_router';

	$functionName($router);
}

$router->error(function (Request $request, Response $response, Exception $e) {
	$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
	return Blade::render('error/exception', ['e' => $e]);
});
$router->run();
