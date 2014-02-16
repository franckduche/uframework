<?php

require __DIR__ . '/../vendor/autoload.php';

use Model\DatabaseFinder;
use Model\StatusMapper;
use Model\Status;
use Model\Connection;
use Http\Request;
use Exception\StatusNotFoundException;
use Exception\HttpException;

/**
 *  Config
 */
$debug = true;

$app = new \App(new View\TemplateEngine(
    __DIR__ . '/templates/'
), $debug);

/** 
 * DB Connection
 */
$dsn = 'mysql:dbname=uframework;host=127.0.0.1';
$user = 'uframework';
$password = 'passw0rd';
$connection = null;
try {
	$connection = new Connection($dsn, $user, $password);
} catch (PDOException $e) {
	echo 'Connection failed : ' . $e->getMessage();
}

$finder = new DatabaseFinder($connection);
$mapper = new StatusMapper($connection);

/**
 * Add listener
 */
$app->addListener('process.before', function (Request $request) use ($app) {
    session_start();

    $allowed = [
        '/statuses' => [ Request::GET, Request::POST, Request::DELETE ],
        '/login' => [ Request::GET, Request::POST ],
        '/' => [ Request::GET ]
    ];

    if (isset($_SESSION['is_authenticated'])
        && true === $_SESSION['is_authenticated']) {
        return;
    }

    foreach ($allowed as $pattern => $methods) {
        if (preg_match(sprintf('#^%s$#', $pattern), $request->getUri())
            && in_array($request->getMethod(), $methods)) {
            return;
        }
    }

	return $app->redirect('/login');
});

/**
 * Index
 */
$app->get('/', function (Request $request) use ($app) {
    $app->redirect('/statuses', array(), 302);
});

/**
 * Login page.
 */
$app->get('/login', function () use ($app) {
    return $app->render('login.php');
});

/**
 * Login
 */
$app->post('/login', function (Request $request) use ($app) {
    $username = $request->getParameter('username');
    $pass = $request->getParameter('password');

    if (!empty($username) && !empty($pass)) {
        $_SESSION['is_authenticated'] = true;
		$_SESSION['username'] = $username;

        return $app->redirect('/');
    }

    return $app->render('login.php', [ 'username' => $username ]);
});

/**
 * Logout.
 */
$app->get('/logout', function (Request $request) use ($app) {
    session_destroy();

    return $app->redirect('/');
});

/**
 * Show all statuses.
 */
$app->get('/statuses', function (Request $request) use ($app, $finder) {
	$format = $request->guessBestFormat();

	try {
		$criteria['ORDER BY'] = $request->getParameter('orderBy');
		$criteria['LIMIT 0, '] = $request->getParameter('limit');
		$statuses = $finder->findAll($criteria);
	} catch (StatusNotFoundException $e) {
		$statuses = array();
	}
	
	if ($format === "application/json") {
		return new Response(json_encode($statuses), 200, array('Content-Type' => 'application/json'));
	}
	return $app->render('statuses.php', array('statuses' => $statuses));
});

/**
 * Show the specified status.
 */
$app->get('/statuses/(\w+)', function (Request $request, $id) use ($app, $finder) {
	$format = $request->guessBestFormat();
	
	try {
		$status = $finder->findOneById($id);
	} catch (StatusNotFoundException $e) {
		throw new HttpException(404, $e->getMessage(), $e);
	}
	
	if ($format === "application/json") {
		return new Response(json_encode($status), 200, array('Content-Type' => 'application/json'));
	}
	return $app->render('status.php', array('status' => $statuses));
});

/**
 * Add the status posted.
 */
$app->post('/statuses', function (Request $request) use ($app, $finder, $mapper) {
	try {
		$status = new Status($request->getParameter('username'), $request->getParameter('content'));
		$mapper->persist($status);
	} catch (StatusNotFoundException $e) {
		throw new HttpException(404, $e->getMessage(), $e);
	}
	
    $app->redirect('/statuses');
});

/**
 * Delete the specified status.
 */
$app->delete('/statuses/(\w+)', function (Request $request, $id) use ($app, $finder, $mapper) {
	try {
		$statusToDelete = $finder->findOneById($id);
		$mapper->remove($statusToDelete);
	} catch (StatusNotFoundException $e) {
		throw new HttpException(404, $e->getMessage(), $e);
	}
	
	$app->redirect('/statuses');
});

return $app;
