<?php
include('dbconnection.php');

$base_url = 'http://' . $_SERVER['HTTP_HOST'];

if (isset($_POST['submit'])) {
    $username = $_POST['email'];
    $password = $_POST['password'];

    $response = array();

    // Haal het opgeslagen gehashte wachtwoord op basis van het e-mailadres
    $getPasswordQuery = "SELECT customer_id, email, password FROM customers WHERE email = '$username'";
    $getPasswordResult = mysqli_query($conn, $getPasswordQuery);

    if ($getPasswordResult) {
        if ($row = mysqli_fetch_assoc($getPasswordResult)) {
            $hashedPassword = $row['password'];

            // Vergelijk het ingevoerde wachtwoord met het opgeslagen gehashte wachtwoord
            if (password_verify($password, $hashedPassword)) {
                $response['success'] = true;
                $response['message'] = 'Login successful';
                $response['redirect'] = $base_url . '/hiveinsight/dashboard.php';
                $response['customer_id'] = $row['customer_id'];

                ini_set('session.cookie_lifetime', 86400);
                session_start();
                $_SESSION['customer_id'] = $response['customer_id'];
            } else {
                $response['success'] = false;
                $response['message'] = 'Invalid username or password';

                header('Location: ' . $base_url . '/hiveinsight/login.php?login_error=1');
                exit();
            }
        } else {
            $response['success'] = false;
            $response['message'] = 'Invalid username or password';

            header('Location: ' . $base_url . '/hiveinsight/login.php?login_error=1');
            exit();
        }
    } else {
        $response['success'] = false;
        $response['message'] = 'Error executing query';

        header('Location: ' . $base_url . '/hiveinsight/login.php?login_error=1');
        exit();
    }

    if ($response['success'] && isset($response['redirect'])) {
        header('Location: ' . $response['redirect']);
        exit();
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
