<?php

require __DIR__ . '/../vendor/autoload.php';

use Model\DatabaseFinder;
use Model\StatusMapper;
use Model\Status;
use Model\Connection;
use Http\Request;
use Exception\StatusNotFoundException;
use Exception\HttpException;

// Config
$debug = true;

$app = new \App(new View\TemplateEngine(
    __DIR__ . '/templates/'
), $debug);

// DB Connection
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
 * Index
 */
$app->get('/', function (Request $request) use ($app) {
    $app->redirect('/statuses', array(), 302);
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
$app->delete('/statuses/(\w+)', function (Request $request, $id) use ($app, $finder) {
	try {
		$statusToDelete = $finder->findOneById($id);
		$statuses = $finder->findAll();
		foreach ($statuses as $index => $status) {
			if ($status->getId() === $statusToDelete->getId()) {
				unset($statuses[$index]);
			}
		}
	} catch (StatusNotFoundException $e) {
		throw new HttpException(404, $e->getMessage(), $e);
	}
	
	$app->redirect('/statuses');
});

return $app;
