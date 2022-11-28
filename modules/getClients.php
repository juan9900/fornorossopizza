<?php 

include('db.php');
$subscribedForno = 1;
$sql = 'SELECT COUNT(*) as clientsCount FROM clients WHERE subscribedForno = ?';
if (!($stmt = $db->prepare($sql))) {
    echo "Prepare failed: (" . $db->errno . ") " . $db->error;
}

if (!$stmt->bind_param('i',$subscribedForno)) {
    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
}

if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
}

$result = $stmt->get_result();
$clients = $result->fetch_all(MYSQLI_ASSOC);
$totalClients = $clients[0]['clientsCount'];

