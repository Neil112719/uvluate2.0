<?php
include 'cors.php';
include 'database_connection.php';

// Check if the table is empty
$query = $pdo->prepare("SELECT COUNT(*) as count FROM users");
$query->execute();
$result = $query->fetch();

if ($result['count'] == 0) {
    // Table is empty, create the default admin
    $id_number = 1;
    $password = password_hash('admin', PASSWORD_BCRYPT); // Hash the password
    $fname = 'Admin';
    $lname = 'User';
    $email = 'antonneilandales18@gmail.com'; // Placeholder email
    $usertype = 1; // Assuming '1' indicates an admin user

    // Insert the admin user
    $insertQuery = $pdo->prepare("INSERT INTO users (id_number, password, fname, lname, email, usertype) 
                                  VALUES (:id_number, :password, :fname, :lname, :email, :usertype)");
    $insertQuery->execute([
        'id_number' => $id_number,
        'password' => $password,
        'fname' => $fname,
        'lname' => $lname,
        'email' => $email,
        'usertype' => $usertype
    ]);

    echo json_encode(['status' => 'success', 'message' => 'Admin account created.']);
} else {
    echo json_encode(['status' => 'exists', 'message' => 'Admin account already exists or table is not empty.']);
}
?>
