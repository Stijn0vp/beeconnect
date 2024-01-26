<?php
$base_url = 'http://' . $_SERVER['HTTP_HOST'];

ini_set('session.cookie_path', '/hiveinsight');
session_start();

$api_url = $base_url . '/hiveinsight/includes/login.api.php';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // Skip SSL certificate verification
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // Skip SSL certificate verification

$api_data_json = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}

curl_close($ch);

$response = json_decode($api_data_json, true);

if (isset($_SESSION['customer_id'])) {
    header("Location: " . $base_url . "/hiveinsight/dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Beeconnect</title>
    <link rel="icon" href="styles/icon.ico" type="image/x-icon">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="styles/input-page.css">
</head>

<body>
    <section>
        <div class="form-box-login">
            <div class="form-value">
                <h2>HiveInsight Login</h2>
                <form name="loginForm" id='' action='<?php echo $api_url; ?>' onsubmit="return isValid()" method="POST">
                    <div class="inputbox">
                        <ion-icon name="mail-outline"></ion-icon>
                        <input type="email" id="email" name="email" required>
                        <label for="username">Email</label>
                    </div>
                    <div class="inputbox">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        <input type="password" id="password" name="password" required>
                        <label for="password">Password</label>
                    </div>
                    <div class="forget">
                        <label></label>
                        <a href="#">Forgot Password</a>
                    </div>
                    
                    <button type="submit" id="btn" value="Login" name="submit">Log In</button>

                    <div class="register">
                        <p>Don't have an account? <a onclick="window.location.href='register.php'" id="btn">Register</a></p>
                    </div>
                    <div class="register">
                        <p><a onclick="window.location.href='https://192.168.159.70/index.html'" id="btn">Back to homepage</a></p>
                    </div>
                    <div>
                        <?php
                        if (isset($_GET['login_error']) && $_GET['login_error'] == 1) {
                            echo '<p style="color: red;">Invalid login credentials. Please try again.</p>';
                        }
                        ?>
                    </div>
                </form>
            </div>
        </div>
    </section>
</body>

</html>
