<?php

namespace Alura\Pdo\Infrastructure\Repository;

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Domain\Repository\StudentRepository;
use Alura\Pdo\Infrastructure\Persistence\ConnectionCreator;

class PdoStudentRepository implements StudentRepository
{

    private \PDO $connection;
    public function __construct()
    {
        $this->connection = ConnectionCreator::createConnection();
    }

    public function allStudents(): array
    {
        // TODO: Implement allStudents() method.
    }

    public function studentsBirthDateAt(\DateTimeInterface $birthDate): array
    {
        // TODO: Implement studentsBirthDateAt() method.
    }

    public function save(Student $student): bool
    {
        if($student->id() === null){
            $this->insert($student);
        }

        return $this->update($student);
    }

    public function insert(Student $student): bool
    {
        $sqlInsert = "INSERT INTO students (name, birth_date) VALUES (:name, :birth_date)";
        $statement = $this->connection->prepare($sqlInsert);
        $responseInsert = $statement->execute([
            ":name", $student->name(),
            ":birth_date", $student->birthDate()->format('Y-m-d')
        ]);

        if($responseInsert){
            $student->defineId($this->connection->lastInsertId());
        }

        return $responseInsert;
    }

    public function remove(Student $student): bool
    {
        $sqlDelete = "DELETE FROM students WHERE id = ?";
        $prepare = $this->connection->prepare($sqlDelete);
        $prepare->bindValue(1, $student->id(), PDO::PARAM_INT);
        return $prepare->execute();
    }

    public function update(Student $student): bool{
        $updateQuery = "UPDATE students name = :name, birth_date = :birth_date WHERE id = :id;";
        $statement = $this->connection->prepare($updateQuery);
        return $statement->execute([
            ":name", $student->name(),
            ":birth_date", $student->birthDate()->format('Y-m-d')
        ]);
    }
}