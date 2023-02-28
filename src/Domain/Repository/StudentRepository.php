<?php

namespace Alura\Pdo\Domain\Repository;

use Alura\Pdo\Domain\Model\Student;

interface StudentRepository
{
    public function allStudents(): array;
    public function studentsWithPhones(): array;

    public function studentsBirthDateAt(\DateTimeInterface $birthDate): array;
    public function save(Student $studant): bool;
    public function remove(Student $student): bool;

}