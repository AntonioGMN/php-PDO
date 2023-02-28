<?php

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Infrastructure\Persistence\ConnectionCreator;
use Alura\Pdo\Infrastructure\Repository\PdoStudentRepository;

require_once 'vendor/autoload.php';

$connection = ConnectionCreator::createConnection();
$repository = new PdoStudentRepository($connection);

$connection->beginTransaction();

try {
    $student = new Student(null, 'Neto', new \DateTimeImmutable('1999-07-11'));
    $repository->save($student);

    $student2 = new Student(null, 'Estudante 2', new \DateTimeImmutable('1999-07-11'));
    $repository->save($student2);

    $connection->commit();
}catch (RuntimeException $err){
    echo $err->getMessage();
    $connection->rollBack();
}
//var_dump($repository->save($student));



echo PHP_EOL;

