<?php
include_once('db.php');
if (!isset($_POST['username'], $_POST['password'])) {
    header("Location: ../dashboard.php");
}
if ($stmt = $db->prepare('SELECT id, password from users WHERE username = ?')) {
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    // Store the result so we can check if the account exists in the database.
    $stmt->store_result();
}
if ($stmt->num_rows > 0) {
    $stmt->bind_result($id, $password);
    $stmt->fetch();
    // Account exists, now we verify the password.
    $enteredPassword = hash('sha256', $_POST['password']);
    if ($enteredPassword === $password) {
        session_start();
        // Create sessions, so we know the user is logged in, they basically act like cookies but remember the data on the server.
        $_SESSION['loggedin'] = true;
        $_SESSION['name'] = $_POST['username'];
        $_SESSION['id'] = $id;
        if ($_POST['username'] == 'admin') {
            $_SESSION['level'] = 'write';
        } else {
            $_SESSION['level'] = 'read';
        }
        header("Location: ../dashboardIndex.php");
    } else {
        header("Location: ../dashboard.php?err=1");
    }
} else {
    header("Location: ../dashboard.php?err=1");
}
$stmt->close();
