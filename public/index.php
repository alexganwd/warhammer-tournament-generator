<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use WTG\WTGHome;
use WTG\WTGSignup;
use WTG\WTGLogin;
use WTG\WTGAdmin;
use WTG\WTGLogout;
use WTG\WTGPdo;
use FastRoute\RouteCollector;
use Middlewares\FastRoute;
use Middlewares\RequestHandler;
use Narrowspark\HttpEmitter\SapiEmitter;
use Relay\Relay;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use function DI\get;
use function DI\create;
use function FastRoute\simpleDispatcher;

require_once dirname(__DIR__) . '/vendor/autoload.php';
$loader = new FilesystemLoader(__DIR__.'/../templates/');


session_start();
# Setting up html templating as dependency #
$twig = new Environment($loader, ['debug' => true]);
$twig->addGlobal('session', $_SESSION);


$dotenv = Dotenv\Dotenv::create(__DIR__.'/../');
$dotenv->load();

# Using injection dependency design patter with a container to manage dependencies and keep a cleaner code. I prefer this than a singleton pattern.

$containerBuilder = new ContainerBuilder();
$containerBuilder->useAutowiring(false);   // Don't need guessed injections, too small project for it - and I'm new to this design pattern
$containerBuilder->useAnnotations(false);  // Don't need annotations yet
$containerBuilder->addDefinitions([
    WTGHome::class => create(WTGHome::class)->constructor(get('Twig'), get('Response')),
    'Twig' => $twig,
    'Response' => function() {
        return new Response();
    },
    WTGSignup::class => create(WTGSignup::class)->constructor(get('Database'), get('Response')),
    'Database' => function() {
        return new WTGPdo("mysql:host=".getenv('DB_HOST').";dbname=".getenv('DB_NAME'),getenv('DB_USER'),getenv('DB_PASS'));
    },
    'Response' => function() {
        return new Response();
    },
    WTGLogin::class => create(WTGLogin::class)->constructor(get('Database'), get('Response')),
    'Database' => function() {
        return new WTGPdo("mysql:host=".getenv('DB_HOST').";dbname=".getenv('DB_NAME'),getenv('DB_USER'),getenv('DB_PASS'));
    },
    'Response' => function() {
        return new Response();
    },
    WTGAdmin::class => create(WTGAdmin::class)->constructor(get('Twig'), get('Response')),
    'Twig' => $twig,
    'Response' => function() {
        return new Response();
    },
    WTGLogout::class => create(WTGLogout::class)->constructor(get('Response')),
    'Response' => function() {
        return new Response();
    },
]);



# Build dependencies and create the container with the object compiled.
$container = $containerBuilder->build();

$routes = simpleDispatcher(function(RouteCollector $r) {
    $r->get('/', WTGHome::class);
    $r->post('/signup', WTGSignup::class);
    $r->post('/login', WTGLogin::class);
    $r->get('/admin', WTGAdmin::class);
    $r->get('/logout', WTGLogout::class);


});

$middlewareQueue[] = new FastRoute($routes);
$middlewareQueue[] = new RequestHandler($container);

$requestHandler = new Relay($middlewareQueue);
$response = $requestHandler->handle(ServerRequestFactory::fromGlobals());  // This is where request enters our middleware

$emitter = new SapiEmitter();
return $emitter->emit($response);