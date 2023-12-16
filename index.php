<?php

# Load dependencies
require 'vendor/autoload.php';

use duncan3dc\Laravel\Blade;
use duncan3dc\Laravel\BladeInstance;
use duncan3dc\Laravel\Directives;
use Tamtamchik\SimpleFlash\Flash;
use Tamtamchik\SimpleFlash\TemplateFactory;
use Tamtamchik\SimpleFlash\Templates;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

function register_all_files($match)
{
	foreach (glob($match) as $route) {
		require $route;
	}
	return null;
}

# Config php vars
ini_set('session.cookie_httponly', 1);
ini_set('session.auto_start', 0);

# Load environment variables.
$dotenv = Dotenv\Dotenv::createImmutable('./');
$dotenv->load();

# Load all system files
require_once './index.files.php';

# Start the HTML template engine
$public = (new Directives())->withCss('/public')->withJs('/public');
Blade::setInstance(new BladeInstance(__DIR__ . '/views', __DIR__ . '/cache', $public));

# Try connect to databases.
use App\Auth as AppAuth;
try {
	# Load the credentials of the database.
	$dbHostname = $_ENV['DB_HOST'];
	$dbName = $_ENV['DB_NAME'];
	$dbUsername = $_ENV['DB_USER'];
	$dbPassword = $_ENV['DB_PASS'];
	$dbPrefix = $_ENV['DB_PREFIX'];

	# Connect to database (Auth lib)
	$pdoConnection = new PDO("mysql:host=$dbHostname;dbname=$dbName", $dbUsername, $dbPassword);
	AppAuth::createInstance($pdoConnection, $dbPrefix);

	# Connect to database (MySQLDb lib)
	$mysqliConnection = new mysqli($dbHostname, $dbUsername, $dbPassword, $dbName);
	$dbConnection = new MysqliDb($mysqliConnection);
	$dbConnection->setPrefix($dbPrefix);
} catch (Exception $e) {
	echo Blade::render('exception', ['e' => $e]);
	exit();
}

# Initialize flash alerts
$template = TemplateFactory::create(Templates::BOOTSTRAP);
$GLOBALS['flash'] = new Flash($template);

# Initialize application routes.
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
	$functionName = str_replace('.php', '', $functionName);
	$functionName = strtolower($functionName);
	$functionName .= '_app_router';

	$functionName($router);
}

$router->error(function (Request $request, Response $response, Exception $e) {
	$response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
	return Blade::render('exception', ['e' => $e]);
});
$router->run();
