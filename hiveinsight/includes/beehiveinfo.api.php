<?php

function GetInfo($customer_id, $arduino_id)
{

    include("dbconnection.php");
    $response = array();

    if ($arduino_id === null) {
        $response['success'] = false;
        $response['message'] = 'Arduino ID or Customer ID not provided.';
    } else {
        if ($conn) {
            $sql = "select * from beehives where customers_customer_id = $customer_id and arduino_id = $arduino_id";
        
            $result = mysqli_query($conn, $sql);
            if ($result) {
                $i = 0;
                while ($row = mysqli_fetch_assoc($result)) {
                    $response[$i]['smarthive_name'] = $row['smarthive_name'];
                    $response[$i]['smarthive_location'] = $row['smarthive_location'];
                    $response[$i]['arduino_id'] = $row['arduino_id'];
                    $response[$i]['ConnectID'] = $row['ConnectID'];
                    $response[$i]['status'] = $row['status'];
                    $response[$i]['reading_time'] = $row['reading_time'];
                    $i++;
                }
                return json_encode($response, JSON_PRETTY_PRINT);
            }
        } else {
            echo "Database connection failed";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include("dbconnection.php");
    
    ini_set('session.cookie_path', '/hiveinsight');
    session_start();

    $customer_id = $_SESSION['customer_id'];
    $arduino_id = $_SESSION['arduino_id'];

    $response = array();

    if ($arduino_id === null) {
        $response['success'] = false;
        $response['message'] = 'Arduino ID not provided.';
    } else {
        // Verwijder sensordata voor de beehive
        $delete_sensordata_query = "DELETE FROM sensordata WHERE beehives_arduino_id = $arduino_id";
        $result_sensordata = mysqli_query($conn, $delete_sensordata_query);

        if ($result_sensordata) {
            // Verwijder de beehive
            $delete_beehive_query = "DELETE FROM beehives WHERE customers_customer_id = $customer_id and arduino_id = $arduino_id";
            $result_beehive = mysqli_query($conn, $delete_beehive_query);

            if ($result_beehive) {
                $response['success'] = true;
                $response['message'] = 'Beehive successfully deleted.';
            } else {
                $response['success'] = false;
                $response['message'] = 'Failed to delete beehive: ' . mysqli_error($conn);
            }
        } else {
            $response['success'] = false;
            $response['message'] = 'Failed to delete sensordata: ' . mysqli_error($conn);
        }
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}





