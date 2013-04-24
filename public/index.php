<?php
require '../vendor/autoload.php';

// Prepare app
$app = new \Slim\Slim(array(
    'templates.path' => '../templates',
    'log.level' => 4,
    'log.enabled' => true,
    'log.writer' => new \Slim\Extras\Log\DateTimeFileWriter(array(
        'path' => '../var/logs',
        'name_format' => 'y-m-d'
    ))
));

// Se configurar las variables que posteriormente van a usarse
$req = $app->request();
$accept = $req->headers('Accept');

if ($accept == 'application/json') {
    $res = $app->response();
    $res['Content-Type'] = 'application/json';
}

require_once '../app/lib/database.php';
require_once '../app/lib/session_manager.php';

$db = Database::getInstance();

// Prepare view
\Slim\Extras\Views\Twig::$twigOptions = array(
    'charset' => 'utf-8',
    'cache' => realpath('../var/cache'),
    'auto_reload' => true,
    'strict_variables' => false,
    'autoescape' => true
);

require_once '../app/lib/twig_session_extension.php';
\Slim\Extras\Views\Twig::$twigExtensions = array(
    'Twig_Extensions_Slim',
    'Session_Extension'
);

$app->view(new \Slim\Extras\Views\Twig());

// Define routes
require_once '../app/routes/songs.php';
require_once '../app/routes/users.php';
require_once '../app/routes/sessions.php';
require_once '../app/routes/public.php';

// Run app
$app->run();