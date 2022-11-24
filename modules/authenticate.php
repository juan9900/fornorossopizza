<?php 
include_once('db.php');
if (!isset($_POST['username'],$_POST['password'])) {
    header("Location: ../dashboardLogin.php");
}

    if ($stmt = $db->prepare('SELECT id, password from users WHERE username = ?')){
        $stmt->bind_param('s',$_POST['username']);
        $stmt->execute();
        // Store the result so we can check if the account exists in the database.
        $stmt->store_result();

    }

    if($stmt->num_rows > 0){
        $stmt->bind_result($id, $password);
        $stmt->fetch();
        // Account exists, now we verify the password.
        if($_POST['password'] === $password){
            session_start();
            // Create sessions, so we know the user is logged in, they basically act like cookies but remember the data on the server.
            $_SESSION['loggedin'] = true;
            $_SESSION['name'] = $_POST['username'];
            $_SESSION['id'] = $id;
            header("Location: ../dashboardIndex.php");
        }else{
            header("Location: ../dashboardLogin.php?err=1");        }
    }else{
        header("Location: ../dashboardLogin.php?err=1");    }
?>