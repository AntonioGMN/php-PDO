<?php

$databasePath = __DIR__ . "/database.sqlite";
$pdo = new PDO("sqlite:". $databasePath);

$pdo->exec("INSERT INTO phones (area_code, number, student_id) VALUES ('84', '988456636', 3), ('84', '222222222', 4)");
exit();

$createTableSql = '
    CREATE TABLE IF NOT EXISTS students (
        id INTEGER PRIMARY KEY,
        name TEXT,
        birth_date TEXT
    );

    CREATE TABLE IF NOT EXISTS phones (
        id INTEGER PRIMARY KEY,
        area_code TEXT,
        number TEXT,
        student_id INTEGER,
        FOREIGN KEY(student_id) REFERENCES students(id)
    );
';

$pdo->exec($createTableSql);

echo "conectei" . PHP_EOL;