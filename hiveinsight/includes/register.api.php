<?php
include('dbconnection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $response = array();

    $checkQuery = "SELECT * FROM customers WHERE email = '$email'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if ($checkResult) {
        if (mysqli_num_rows($checkResult) > 0) {
            $response['success'] = false;
            $response['message'] = 'This email is already in use';
        } else {
            // Insert the user if the email doesn't exist
            $sql = "INSERT INTO customers (username, email, phone_number, password) VALUES ('$username', '$email', '$phone_number', '$hashedPassword')";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                $response['success'] = true;
                $response['message'] = 'Registration successful!';
            } else {
                $response['success'] = false;
                $response['message'] = 'Registration failed. ' . mysqli_error($conn);
            }
        }
    } else {
        $response['success'] = false;
        $response['message'] = 'Unable to check the email';
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
