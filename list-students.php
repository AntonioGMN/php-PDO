<?php

use Alura\Pdo\Infrastructure\Persistence\ConnectionCreator;
use Alura\Pdo\Infrastructure\Repository\PdoStudentRepository;

require_once 'vendor/autoload.php';

$connection = ConnectionCreator::createConnection();

$sqlQuery = 'SELECT * FROM phones;';
$stmt = $connection->query($sqlQuery);
$resposne = $stmt->fetchAll(PDO::FETCH_ASSOC);
var_dump($resposne);

$repository = new PdoStudentRepository($connection);
$students = $repository->allStudents();

//
//foreach ($studentsDataList as $studentData){
//    $studentsList = new Student(
//        $studentData['id'],
//        $studentData['name'],
//        new \DateTimeImmutable($studentData['birth_date'])
//    );
//}

var_dump($students);