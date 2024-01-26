<?php
include('dbconnection.php');


// Random number generator voor arduino_id
function generateUniqueNumber1($conn, $maxAttempts1 = 1000) {
    for ($attempt = 0; $attempt < $maxAttempts1; $attempt++) {
        $randomNumber1 = rand(10, 9999999);

        $query = "SELECT COUNT(*) as count FROM beehives WHERE arduino_id = $randomNumber1";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_fetch_assoc($result)['count'] == 0) {
            return $randomNumber1;
        }

        usleep(1000);
    }
    throw new Exception("Kan geen uniek nummer genereren na $maxAttempts1 pogingen.");
}

// Random number generator voor Connect_ID
function generateUniqueNumber2($conn, $maxAttempts2 = 1000) {
    for ($attempt = 0; $attempt < $maxAttempts2; $attempt++) {
        $randomNumber2 = rand(9999999999, 99999999999);

        $query = "SELECT COUNT(*) as count FROM beehives WHERE ConnectID = $randomNumber2";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_fetch_assoc($result)['count'] == 0) {
            return $randomNumber2;
        }

        usleep(1000);
    }
    throw new Exception("Kan geen uniek nummer genereren na $maxAttempts2 pogingen.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    ini_set('session.cookie_path', '/hiveinsight');
    session_start();
    $customer_id = mysqli_real_escape_string($conn, $_POST['customer_id']);
    $beehive_name = mysqli_real_escape_string($conn, $_POST['name']);
    $beehive_location = mysqli_real_escape_string($conn, $_POST['location']);

    $notint_arduino_id = generateUniqueNumber1($conn);
    $arduino_id = (int) $notint_arduino_id;

    $notint_ConnectID = generateUniqueNumber2($conn);
    $ConnectID = (int) $notint_ConnectID;

    $response = array();

    $sql = "INSERT INTO beehives (customers_customer_id, arduino_id, status, smarthive_name, smarthive_location, ConnectID) 
    VALUES ('$customer_id', '$arduino_id', 'Disabled', '$beehive_name', '$beehive_location', '$ConnectID')";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        $response['success'] = true;
        $response['message'] = 'Registratie succesvol';
        $response['ConnectID'] = $ConnectID;
    } else {
        $response['success'] = false;
        $response['message'] = 'Fout bij registreren beehive: ' . mysqli_error($conn);
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
