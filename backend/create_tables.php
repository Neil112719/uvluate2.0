<?php
header("Content-Type: text/html; charset=UTF-8");

require 'database_connection.php'; // Include your existing database connection file

// Function to check if a table exists
function tableExists($pdo, $tableName) {
    try {
        $result = $pdo->query("SELECT 1 FROM $tableName LIMIT 1");
    } catch (Exception $e) {
        return false;
    }
    return $result !== false;
}

// Function to create tables
function createTable($pdo, $tableName, $createSQL) {
    try {
        $pdo->exec($createSQL);
        echo "Table '$tableName' created successfully.<br>";
    } catch (Exception $e) {
        echo "Error creating table '$tableName': " . $e->getMessage() . "<br>";
    }
}

// Define the SQL statements to create tables
$tables = [
    'users' => "
        CREATE TABLE `users` (
	`id_number` INT(10) NOT NULL DEFAULT '0',
	`password` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`fname` TEXT NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`middlename` TEXT NULL DEFAULT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`lname` TEXT NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`suffix` TEXT NULL DEFAULT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`email` VARCHAR(50) NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`usertype` INT(10) NOT NULL,
	`department` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`course` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`section` TEXT NULL DEFAULT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`year` INT(10) NULL DEFAULT NULL,
	`otp` INT(10) NULL DEFAULT NULL,
	`timestamp` TIMESTAMP NULL DEFAULT NULL,
	PRIMARY KEY (`id_number`) USING BTREE,
	UNIQUE INDEX `id_number` (`id_number`) USING BTREE
)
COLLATE='utf8mb4_0900_ai_ci'
ENGINE=InnoDB
;
    ",
    /* Add other tables as needed, for example:
    'another_table' => "
        CREATE TABLE another_table (
            id INT AUTO_INCREMENT PRIMARY KEY,
            example_column VARCHAR(100) NOT NULL
        ) ENGINE=InnoDB;
    "*/
];

// Check and create tables
foreach ($tables as $tableName => $createSQL) {
    if (!tableExists($pdo, $tableName)) {
        echo "Table '$tableName' does not exist. Creating table...<br>";
        createTable($pdo, $tableName, $createSQL);
    } else {
        echo "Table '$tableName' already exists.<br>";
    }
}
?>
