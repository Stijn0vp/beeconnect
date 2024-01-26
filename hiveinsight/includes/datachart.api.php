<?php

function GetData($customer_id, $arduino_id)
{
    include("dbconnection.php");
    $response = array();

    if ($arduino_id === null) {
        $response['success'] = false;
        $response['message'] = 'Smarthive ID or Customer ID not provided.';
    } else {
        if ($conn) {
            $sql = "SELECT
                        SUM(motion0 = 1) AS motion0_count,
                        SUM(motion1 = 1) AS motion1_count,
                        SUM(motion2 = 1) AS motion2_count,
                        SUM(motion3 = 1) AS motion3_count,
                        MAX(CASE WHEN temperature0 < 99999999 THEN temperature0 ELSE NULL END) AS temperature0,
                        MAX(CASE WHEN temperature1 < 99999999 THEN temperature1 ELSE NULL END) AS temperature1,
                        MAX(CASE WHEN temperature2 < 99999999 THEN temperature2 ELSE NULL END) AS temperature2,
                        MAX(CASE WHEN temperature3 < 99999999 THEN temperature3 ELSE NULL END) AS temperature3,
                        MAX(CASE WHEN humidity0 < 99999999 THEN humidity0 ELSE NULL END) AS humidity0,
                        MAX(CASE WHEN humidity1 < 99999999 THEN humidity1 ELSE NULL END) AS humidity1,
                        MAX(CASE WHEN humidity2 < 99999999 THEN humidity2 ELSE NULL END) AS humidity2,
                        MAX(CASE WHEN humidity3 < 99999999 THEN humidity3 ELSE NULL END) AS humidity3,
                        MAX(CASE WHEN airquality0 < 99999999 THEN airquality0 ELSE NULL END) AS airquality0,
                        MAX(CASE WHEN airquality1 < 99999999 THEN airquality1 ELSE NULL END) AS airquality1,
                        MAX(CASE WHEN airquality2 < 99999999 THEN airquality2 ELSE NULL END) AS airquality2,
                        MAX(CASE WHEN airquality3 < 99999999 THEN airquality3 ELSE NULL END) AS airquality3,
                        DATE_FORMAT(reading, '%Y-%m-%d %H:00:00') AS avg_hour
                    FROM
                        sensordata
                    WHERE
                        beehives_arduino_id = $arduino_id
                        AND reading >= NOW() - INTERVAL 12 HOUR
                        AND customers_customer_id = $customer_id
                    GROUP BY
                        avg_hour
                    ORDER BY
                        avg_hour DESC;";

            $result = mysqli_query($conn, $sql);

            if ($result) {
                $i = 0; // Initialiseer de index
                while ($row = mysqli_fetch_assoc($result)) {
                    $response[$i]['motion0'] = $row['motion0_count'];
                    $response[$i]['motion1'] = $row['motion1_count'];
                    $response[$i]['motion2'] = $row['motion2_count'];
                    $response[$i]['motion3'] = $row['motion3_count'];
                    $response[$i]['temperature0'] = $row['temperature0'];
                    $response[$i]['temperature1'] = $row['temperature1'];
                    $response[$i]['temperature2'] = $row['temperature2'];
                    $response[$i]['temperature3'] = $row['temperature3'];
                    $response[$i]['humidity0'] = $row['humidity0'];
                    $response[$i]['humidity1'] = $row['humidity1'];
                    $response[$i]['humidity2'] = $row['humidity2'];
                    $response[$i]['humidity3'] = $row['humidity3'];
                    $response[$i]['airquality0'] = $row['airquality0'];
                    $response[$i]['airquality1'] = $row['airquality1'];
                    $response[$i]['airquality2'] = $row['airquality2'];
                    $response[$i]['airquality3'] = $row['airquality3'];
                    $response[$i]['avg_hour'] = $row['avg_hour'];
                    $i++; // Verhoog de index
                }
                return json_encode($response, JSON_PRETTY_PRINT);
            } else {
                echo "Error in executing the query: " . mysqli_error($conn);
            }
        } else {
            echo "Database connection failed";
        }
    }
}
?>
