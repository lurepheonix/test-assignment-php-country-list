<?php

use Utils\Router;

$conn = new PDO(
    'mysql:host=db;dbname=' . $_ENV['MYSQL_DATABASE'],
    $_ENV['MYSQL_USER'],
    $_ENV['MYSQL_PASSWORD']
);

$container = new DI\Container();
$container->set('connection', $conn);

// $c3 = $container->make('conn2', [
//     'mysql:host=db;dbname=' . $_ENV['MYSQL_DATABASE'],
//     $_ENV['MYSQL_USER'],
//     $_ENV['MYSQL_PASSWORD']
// ]);

$router = (new Router)->execute();
