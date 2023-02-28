<?php

namespace Alura\Pdo\Infrastructure\Repository;

use Alura\Pdo\Domain\Model\Phone;
use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Domain\Repository\StudentRepository;
use DateTimeImmutable;
use PDO;

class PdoStudentRepository implements StudentRepository
{

    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function allStudents(): array
    {
        $sqlQuery = 'SELECT * FROM students;';
        $stmt = $this->connection->query($sqlQuery);


        return $this->hydrateStudentList($stmt);
    }

    private function hydrateStudentList(\PDOStatement $stmt): array
    {
        $studentDataList = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $studentList = [];

        foreach ($studentDataList as $studentData) {
            $studentList[]  = new Student(
                $studentData['id'],
                $studentData['name'],
                new DateTimeImmutable($studentData['birth_date'])
            );

        }

        return $studentList;
    }

    private function fillPhoneOf(Student $student): void{
        $query = 'SELECT id, area_code, number FROM phones WHERE student_id = ?';
        $stmt = $this->connection->prepare($query);
        $stmt->bindValue(1, $student->id(), PDO::PARAM_INT);
        $stmt->execute();

        $phoneDataList = $stmt->fetchAll();
        foreach ($phoneDataList as $phoneData){
            $phone = new Phone(
                $phoneData['id'],
                $phoneData['area_code'],
                $phoneData['number'],
            );

            $student->addPhone($phone);
        }
    }

    public function studentsBirthDateAt(\DateTimeInterface $birthDate): array
    {
        $sqlQuery = 'SELECT * FROM students WHERE birth_date = ?;';
        $stmt = $this->connection->prepare($sqlQuery);
        $stmt->bindValue(1, $birthDate->format('Y-m-d'));
        $stmt->execute();

        return $this->hydrateStudentList($stmt);
    }

    public function save(Student $student): bool
    {
        if($student->id() === null){
            return $this->insert($student);
        }

        return $this->update($student);
    }

    public function insert(Student $student): bool
    {
        $sqlInsert = 'INSERT INTO studenta (name, birth_date) VALUES (:name, :birth_date);';
        $statement = $this->connection->prepare($sqlInsert);

        $responseInsert = $statement->execute([
            ':name' => $student->name(),
            ':birth_date' => $student->birthDate()->format('Y-m-d')
        ]);

        if($responseInsert){
            $student->defineId($this->connection->lastInsertId());
        }

        return $responseInsert;
    }

    public function update(Student $student): bool{
        $updateQuery = "UPDATE students name = :name, birth_date = :birth_date WHERE id = :id;";
        $stmt = $this->connection->prepare($updateQuery);
        $stmt->bindValue(':name', $student->name());
        $stmt->bindValue(':birth_date', $student->birthDate()->format('Y-m-d'));
        $stmt->bindValue(':id', $student->id(), PDO::PARAM_INT);

        echo "vai executar update";
        return $stmt->execute();
    }

    public function remove(Student $student): bool
    {
        $sqlDelete = "DELETE FROM students WHERE id = ?";
        $prepare = $this->connection->prepare($sqlDelete);
        $prepare->bindValue(1, $student->id(), PDO::PARAM_INT);
        return $prepare->execute();
    }


    public function studentsWithPhones(): array
    {
        $query = "SELECT students.id, students.name, 
            students.birth_date, phones.id AS phones_id, 
            phones.area_code, phones.number
            FROM students JOIN phones WHERE students.id = phones.student_id;";
        $stmt = $this->connection->query($query);
        $resulte = $stmt->fetchAll();
        $studentList = [];

        foreach ($resulte as $row){
            if (!array_key_exists($row['id'], $studentList)) {
                $studentList[$row['id']] = new Student(
                    $row['id'],
                    $row['name'],
                    new \DateTimeImmutable($row['birth_date'])
                );
            }
            $phone = new Phone($row['phone_id'], $row['area_code'], $row['number']);
            $studentList[$row['id']]->addPhone($phone);
        }

        return $studentList;
    }

}