<?php

include('db.php');
$subscribedForno = 1;
$sql = 'SELECT id FROM clients';
if (!($stmt = $db->prepare($sql))) {
    echo "Prepare failed: (" . $db->errno . ") " . $db->error;
}

// if (!$stmt->bind_param('i', $subscribedForno)) {
//     echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
// }

if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
}

$stmt->store_result();
$stmt->bind_result($id);

$stmt->fetch();
$totalClients = $stmt->num_rows;
$stmt->close();


// $result = $stmt->get_result();
// $clients = $result->fetch_all(MYSQLI_ASSOC);
// $totalClients = $clients[0]['clientsCount'];

// $stmt->close();
