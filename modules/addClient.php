<?php 

include('db.php');
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $firstName = test_input($_POST['firstName']);
    $lastName = $_POST['lastName'];
    $phoneNumber = test_input($_POST['phoneNumber']);
    $email = test_input($_POST['email']);
    $subscribedForno = 1;
    $errors = [];
    
    if (preg_match('~[0-9]+~', $firstName)) {
        array_push($errors, 'El nombre no puede contener números.');
    }

    if(empty($firstName)){
        array_push($errors, 'El nombre no puede estar vacío.');
    }

    if (preg_match('~[0-9]+~', $lastName)) {
        array_push($errors, 'El apellido no puede contener números.');
    }

    if(empty($lastName)){
        array_push($errors, 'El apellido no puede estar vacío.');
    }

    if(empty($phoneNumber)){
        array_push($errors, 'El número telefónico no puede estar vacío.');
    }else{
        if(preg_match("/[a-z]/i", $_POST['phoneNumber']) ){
            array_push($errors, "Número telefónico invalido.");
        }else{
            if(!preg_match("/^(0)?(414|416|424|412)[0-9]{7}$/", $phoneNumber)){
                array_push($errors, 'El número telefónico es inválido.');
            }
        }
    }

    if(empty($email)){
        array_push($errors, 'El correo electrónico no puede estar vacio');
    }else{
        if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i",$email)){
            array_push($errors,'El correo electrónico es inválido.');
        }
    }



    if(count($errors) > 0){
        die(json_encode([
            'status' => 'fail',
            'message' => 'There where errors found in your request, check errors list for more info.',
            'errors' => $errors
        ]));
    }
    if($phoneNumber[0] == '0'){
        $phoneNumber = ltrim($phoneNumber, $phoneNumber[0]);
    }
    $phoneNumber = '+58 ' . $phoneNumber;

    //CHECK IF USER ALREADY EXISTS
    $sql = "SELECT subscribedForno FROM clients WHERE (phoneNumber = ?) OR (email = ?)";
    if(!$stmt = $db->prepare($sql)){
        
        echo (json_encode([
            'status' => "fail",
            'message' => "Prepare failed: (" . $db->errno . ") " . $db->error,
            'errors' => $errors
        ]));
    }
    
    if(!$stmt->bind_param("ss",$phoneNumber, $email)){
        echo (json_encode([
            'status' => "fail",
            'message' => "Binding failed: (" . $stmt->errno . ") " . $stmt->error,
            'errors' => $errors
        ]));
    }
    
    if(!$stmt->execute()){
        echo (json_encode([
            'status' => "fail",
            'message' => "Executing failed: " . $stmt->errno . ") " . $stmt->error,
            'errors' => $errors
        ]));
    }

    $result = $stmt->get_result();
    $client = $result->fetch_all(MYSQLI_ASSOC);
    if(count($client) < 1){ 
        
        //ADD NEW USER
        $sql = "INSERT INTO clients (firstName, lastName, phoneNumber, email, subscribedForno) VALUES (?, ?, ?, ?, ?)";
        if(!$stmt = $db->prepare($sql)){
            
            echo (json_encode([
                'status' => "fail",
                'message' => "Prepare failed: (" . $db->errno . ") " . $db->error,
                'errors' => $errors
            ]));
        }
        
        if(!$stmt->bind_param("ssssi",$firstName, $lastName, $phoneNumber, $email, $subscribedForno)){
            echo (json_encode([
                'status' => "fail",
                'message' => "Binding failed: (" . $stmt->errno . ") " . $stmt->error,
                'errors' => $errors
            ]));
        }
        
        if(!$stmt->execute()){
            echo (json_encode([
                'status' => "fail",
                'message' => "Executing failed: " . $stmt->errno . ") " . $stmt->error,
                'errors' => $errors
            ]));
        }
        echo (json_encode([
            "status" => 'success',
            "message" => 'User added successfully!'
        ]));
    }else{
        if($client[0]['subscribedForno'] == 0){
            //ADD USER TO FORNO CLUB
            $sql = "UPDATE clients SET subscribedForno = ? WHERE (email = ?) OR (phoneNumber = ?)";
            if(!$stmt = $db->prepare($sql)){
                
                echo (json_encode([
                    'status' => "fail",
                    'message' => "Prepare failed: (" . $db->errno . ") " . $db->error,
                    'errors' => $errors
                ]));
            }
            
            if(!$stmt->bind_param("iss",$subscribedForno, $email, $phoneNumber)){
                echo (json_encode([
                    'status' => "fail",
                    'message' => "Binding failed: (" . $stmt->errno . ") " . $stmt->error,
                    'errors' => $errors
                ]));
            }
            
            if(!$stmt->execute()){
                echo (json_encode([
                    'status' => "fail",
                    'message' => "Executing failed: " . $stmt->errno . ") " . $stmt->error,
                    'errors' => $errors
                ]));
            }
            echo (json_encode([
                "status" => 'success',
                "message" => 'User added successfully!'
            ]));
        }else{
            array_push($errors,'Ups! Parece que ya te encuentras registrado.');
            die(json_encode([
                'status' => 'fail',
                'message' => 'There where errors found in your request, check errors list for more info.',
                'errors' => $errors
            ]));
        }
       
    }



    
}else{
    echo (json_encode([
        'status' => "fail",
        'message' => "Missing information for client registration.",
    ]));
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = strtolower($data);
    return $data;
}