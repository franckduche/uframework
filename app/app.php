<?php

require __DIR__ . '/../vendor/autoload.php';

use Model\InMemoryFinder;
use Model\JsonFinder;
use Model\Status;
use Http\Request;
use Exception\StatusNotFoundException;
use Exception\HttpException;

// Config
$debug = true;

$app = new \App(new View\TemplateEngine(
    __DIR__ . '/templates/'
), $debug);

$dsn = 'mysql:dbname=uframework;host=127.0.0.1';
$user = 'uframework';
$password = 'passw0rd'
$connection = null;
try {
	$connection = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
	echo 'Connection failed : ' . $e->getMessage();
}

// $jsonDataPath = __DIR__ . '/../data/statuses.json';
// $finder = new JsonFinder($jsonDataPath);
$finder = new DatabaseFinder($connection);

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
	try {
		$statuses = $finder->findAll();
	} catch (StatusNotFoundException $e) {
		$statuses = array();
	}
	
    return $app->render('statuses.php', array('statuses' => $statuses));
});

/**
 * Show the specified status.
 */
$app->get('/statuses/(\w+)', function (Request $request, $id) use ($app, $finder) {
	try {
		$status = $finder->findOneById($id);
	} catch (StatusNotFoundException $e) {
		throw new HttpException(404, $e->getMessage(), $e);
	}
	
    return $app->render('status.php', array('status' => $status));
});

/**
 * Add the status posted.
 */
$app->post('/statuses', function (Request $request) use ($app, $finder, $jsonDataPath) {
	try {
		$statuses = $finder->findAll();
		$status = new Status($request->getParameter('username'), $request->getParameter('content'));
		$statuses [] = $status;
		file_put_contents($jsonDataPath, $finder->toJson($statuses));
	} catch (StatusNotFoundException $e) {
		throw new HttpException(404, $e->getMessage(), $e);
	}
	
    $app->redirect('/statuses');
});

/**
 * Delete the specified status.
 */
$app->delete('/statuses/(\w+)', function (Request $request, $id) use ($app, $finder, $jsonDataPath) {
	try {
		$statusToDelete = $finder->findOneById($id);
		$statuses = $finder->findAll();
		foreach ($statuses as $index => $status) {
			if ($status->getId() === $statusToDelete->getId()) {
				unset($statuses[$index]);
			}
		}
		file_put_contents($jsonDataPath, $finder->toJson($statuses));
	} catch (StatusNotFoundException $e) {
		throw new HttpException(404, $e->getMessage(), $e);
	}
	
	$app->redirect('/statuses');
});

return $app;
