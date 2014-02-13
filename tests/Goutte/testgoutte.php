<?php

require_once '/path/to/goutte.phar';

use Goutte\Client;

$client = new Client();
$crawler = $client->request('GET', 'http://localhost:8082');
$form = $crawler->selectButton('Tweet!')->form();
$crawler = $client->submit($form, array('username' => 'toto', 'content' => 'vive le php'));