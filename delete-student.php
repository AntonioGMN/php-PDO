<?php

use Alura\Pdo\Infrastructure\Persistence\ConnectionCreator;
require_once 'vendor/autoload.php';

$connection = ConnectionCreator::createConnection();

$sqlDelete = "DELETE FROM students WHERE id=?";
$prepare = $connection->prepare($sqlDelete);
$prepare->bindValue(1, 1, PDO::PARAM_INT);

var_dump($prepare->execute());