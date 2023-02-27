<?php

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Infrastructure\Persistence\ConnectionCreator;

require_once 'vendor/autoload.php';

$pdo = ConnectionCreator::createConnection();

$student = new Student(null, 'Neto', new \DateTimeImmutable('1999-07-11') );

$sqlInsert = "INSERT INTO students (name, birth_date) VALUES (:name, :birth_date)";
$statement = $pdo->prepare($sqlInsert);
$statement->bindValue(":name", $student->name());
$statement->bindValue(":birth_date", $student->birthDate()->format('Y-m-d'));
$statement->execute();

if($statement->execute()){
    var_dump($pdo->exec($sqlInsert));
}

echo PHP_EOL;

