<?php

use Alura\Pdo\Infrastructure\Persistence\ConnectionCreator;

$pdo = ConnectionCreator::createConnection();

$sqlDelete = "DELETE FROM students WHERE id = ?";
$prepare = $pdo->prepare($sqlDelete);
$prepare->bindValue(1, 7, PDO::PARAM_INT);
var_dump($prepare->execute());