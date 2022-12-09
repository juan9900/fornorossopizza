<?php

include('db.php');

$response = [];
$errors = [];
if (!isset($_POST['id'])) {
    array_push($errors, 'No ID of client received');
    // $response = [
    //     'status' => 'Fail',
    //     'Message' => 'Failed at deleting doctor',
    //     'Errors' => $errors
    // ];
}
$sql = 'DELETE FROM clients WHERE id = ?';

try {
    if (!$stmt = $db->prepare($sql)) {
        throw new Exception('Error preparing sql', $db->errno);
    }

    if (!$stmt->bind_param('i', $_POST['id'])) {
        throw new Exception('Error binding params', $db->errno);
    }

    if (!$stmt->execute()) {
        throw new Exception('Error executing sql', $db->errno);
    }

    echo json_encode(array(
        'result' => 'success',
    ));

    $stmt->close();
} catch (Exception $e) {
    echo json_encode(array(
        'error' => $e->getMessage(),
        'code' => $e->getCode(),
    ));
}
