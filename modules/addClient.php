<?php
error_reporting(E_ALL);

ini_set('display_errors', 1);
include('db.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = test_input($_POST['firstName']);
    $lastName = $_POST['lastName'];
    $phoneNumber = test_input($_POST['phoneNumber']);
    $email = test_input($_POST['email']);
    $birthdate = test_input($_POST['birthdate']);
    $subscribedForno = 1;
    $errors = [];

    if (preg_match('~[0-9]+~', $firstName)) {
        array_push($errors, 'El nombre no puede contener números.');
    }

    if (empty($firstName)) {
        array_push($errors, 'El nombre no puede estar vacío.');
    }

    if (preg_match('~[0-9]+~', $lastName)) {
        array_push($errors, 'El apellido no puede contener números.');
    }

    if (empty($lastName)) {
        array_push($errors, 'El apellido no puede estar vacío.');
    }

    if (empty($phoneNumber)) {
        array_push($errors, 'El número telefónico no puede estar vacío.');
    } else {
        if (preg_match("/[a-z]/i", $_POST['phoneNumber'])) {
            array_push($errors, "Número telefónico invalido.");
        } else {
            if (!preg_match("/^(0)?(414|416|424|412)[0-9]{7}$/", $phoneNumber)) {
                array_push($errors, 'El número telefónico es inválido.');
            }
        }
    }

    if (empty($email)) {
        array_push($errors, 'El correo electrónico no puede estar vacio');
    } else {
        if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i", $email)) {
            array_push($errors, 'El correo electrónico es inválido.');
        }
    }



    if (count($errors) > 0) {
        die(json_encode([
            'status' => 'fail',
            'message' => 'There where errors found in your request, check errors list for more info.',
            'errors' => $errors
        ]));
    }
    if ($phoneNumber[0] == '0') {
        $phoneNumber = ltrim($phoneNumber, $phoneNumber[0]);
    }
    $phoneNumber = '+58 ' . $phoneNumber;

    //CHECK IF USER ALREADY EXISTS
    $sql = 'SELECT id FROM clients WHERE phoneNumber = ? ';
    if (!$stmt = $db->prepare($sql)) {

        array_push($errors, 'Error preparando sql 1' . $db->errno . $db->error);
    }

    if (!$stmt->bind_param("s", $phoneNumber)) {
        array_push($errors, 'Error binding sql 1' . $stmt->errno . $stmt->error);
    }

    if (!$stmt->execute()) {
        array_push($errors, 'Error ejecutando sql 1.' . $stmt->errno . $stmt->error);
    }

    if (count($errors) > 0) {
        die(json_encode([
            'status' => 'fail',
            'message' => 'There where errors found in your request, check errors list for more info.',
            'errors' => $errors
        ]));
    }

    $stmt->store_result();
    $stmt->bind_result($id);

    $stmt->fetch();
    $numberRows = $stmt->num_rows;
    $stmt->close();
    if ($numberRows <= 0) {

        $firstName = ucwords(strtolower($firstName));
        $lastName = ucwords(strtolower($lastName));

        //ADD NEW USER
        $sql = "INSERT INTO clients (firstName, lastName, phoneNumber, email, subscribedForno, birthdate) VALUES (?, ?, ?, ?, ?, ?)";
        if (!$stmt = $db->prepare($sql)) {

            array_push($errors, 'Error preparando sql 2' . $db->errno . $db->error);
        }

        if (!$stmt->bind_param("ssssis", $firstName, $lastName, $phoneNumber, $email, $subscribedForno, $birthdate)) {
            array_push($errors, 'Error binding sql 2' . $stmt->errno . $stmt->error);
        }

        if (!$stmt->execute()) {
            array_push($errors, 'Error ejecutando sql 2.' . $stmt->errno . $stmt->error);
        }

        if (count($errors) > 0) {
            die(json_encode([
                'status' => 'fail',
                'message' => 'There where errors found in your request, check errors list for more info.',
                'errors' => $errors
            ]));
        }
        die(json_encode([
            "status" => 'success',
            "message" => 'User added successfully!'
        ]));
    } else {
        array_push($errors, 'Ups! Parece que ya te encuentras registrado.');
        if (count($errors) > 0) {
            die(json_encode([
                'status' => 'fail',
                'message' => 'There where errors found in your request, check errors list for more info.',
                'errors' => $errors
            ]));
        }
    }


    $stmt->close();
} else {
}


function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = strtolower($data);
    return $data;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

</body>

</html>