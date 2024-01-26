<?php

function GetData($customer_id)
{
    include("dbconnection.php");
    $response = array();
    
    if ($customer_id === null) {
        $response['success'] = false;
        $response['message'] = 'Customer ID not provided.';
    } else {
        if ($conn) {
            $sql = "SELECT
            b.arduino_id,
            b.smarthive_name AS name,
            b.smarthive_location AS location,
            b.arduino_id,
            sd.reading AS reading_time,
            ROUND(
                COALESCE(
                    (
                        COALESCE(NULLIF(sd.temperature0, 99999999), 0) + COALESCE(NULLIF(sd.temperature1, 99999999), 0) + COALESCE(NULLIF(sd.temperature2, 99999999), 0) + COALESCE(NULLIF(sd.temperature3, 99999999), 0)
                    ) / NULLIF(
                        (
                            CASE
                                WHEN NULLIF(sd.temperature0, 99999999) IS NOT NULL THEN 1
                                ELSE 0
                            END
                        ) + (
                            CASE
                                WHEN NULLIF(sd.temperature1, 99999999) IS NOT NULL THEN 1
                                ELSE 0
                            END
                        ) + (
                            CASE
                                WHEN NULLIF(sd.temperature2, 99999999) IS NOT NULL THEN 1
                                ELSE 0
                            END
                        ) + (
                            CASE
                                WHEN NULLIF(sd.temperature3, 99999999) IS NOT NULL THEN 1
                                ELSE 0
                            END
                        ),
                        0
                    ),
                    0
                ),
                1
            ) AS temperature,
            ROUND(
                COALESCE(
                    (
                        COALESCE(NULLIF(sd.humidity0, 99999999), 0) + COALESCE(NULLIF(sd.humidity1, 99999999), 0) + COALESCE(NULLIF(sd.humidity2, 99999999), 0) + COALESCE(NULLIF(sd.humidity3, 99999999), 0)
                    ) / NULLIF(
                        (
                            CASE
                                WHEN NULLIF(sd.humidity0, 99999999) IS NOT NULL THEN 1
                                ELSE 0
                            END
                        ) + (
                            CASE
                                WHEN NULLIF(sd.humidity1, 99999999) IS NOT NULL THEN 1
                                ELSE 0
                            END
                        ) + (
                            CASE
                                WHEN NULLIF(sd.humidity2, 99999999) IS NOT NULL THEN 1
                                ELSE 0
                            END
                        ) + (
                            CASE
                                WHEN NULLIF(sd.humidity3, 99999999) IS NOT NULL THEN 1
                                ELSE 0
                            END
                        ),
                        0
                    ),
                    0
                ),
                1
            ) AS humidity,
            ROUND(
                COALESCE(
                    (
                        COALESCE(NULLIF(sd.motion0, 99999999), 0) + COALESCE(NULLIF(sd.motion1, 99999999), 0) + COALESCE(NULLIF(sd.motion2, 99999999), 0) + COALESCE(NULLIF(sd.motion3, 99999999), 0)
                    ) / NULLIF(
                        (
                            CASE
                                WHEN NULLIF(sd.motion0, 99999999) IS NOT NULL THEN 1
                                ELSE 0
                            END
                        ) + (
                            CASE
                                WHEN NULLIF(sd.motion1, 99999999) IS NOT NULL THEN 1
                                ELSE 0
                            END
                        ) + (
                            CASE
                                WHEN NULLIF(sd.motion2, 99999999) IS NOT NULL THEN 1
                                ELSE 0
                            END
                        ) + (
                            CASE
                                WHEN NULLIF(sd.motion3, 99999999) IS NOT NULL THEN 1
                                ELSE 0
                            END
                        ),
                        0
                    ),
                    0
                ),
                1
            ) AS motion
        FROM
            beehives b
            LEFT JOIN (
                SELECT
                    sdi.beehives_arduino_id,
                    MAX(sdi.reading) AS reading,
                    MAX(sdi.temperature0) AS temperature0,
                    MAX(sdi.temperature1) AS temperature1,
                    MAX(sdi.temperature2) AS temperature2,
                    MAX(sdi.temperature3) AS temperature3,
                    MAX(sdi.humidity0) AS humidity0,
                    MAX(sdi.humidity1) AS humidity1,
                    MAX(sdi.humidity2) AS humidity2,
                    MAX(sdi.humidity3) AS humidity3,
                    MAX(sdi.motion0) AS motion0,
                    MAX(sdi.motion1) AS motion1,
                    MAX(sdi.motion2) AS motion2,
                    MAX(sdi.motion3) AS motion3
                FROM
                    sensordata sdi
                GROUP BY
                    sdi.beehives_arduino_id
            ) sd ON sd.beehives_arduino_id = b.arduino_id
        WHERE
            b.customers_customer_id = " . ($customer_id ?? 'NULL');

            $result = mysqli_query($conn, $sql);
            if ($result) {
                $i = 0;
                while ($row = mysqli_fetch_assoc($result)) {
                    $response[$i]['arduino_id'] = $row['arduino_id'];
                    $response[$i]['name'] = $row['name'];
                    $response[$i]['location'] = $row['location'];
                    $response[$i]['reading_time'] = $row['reading_time'];
                    $response[$i]['temperature'] = $row['temperature'];
                    $response[$i]['humidity'] = $row['humidity'];
                    $i++;
                }
                return json_encode($response, JSON_PRETTY_PRINT);
            }
        } else {
            echo "Database connection failed";
        }

    }
}
?>
