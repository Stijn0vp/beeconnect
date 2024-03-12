<?php
$base_url = 'http://' . $_SERVER['HTTP_HOST'];

ini_set('session.cookie_path', '/hiveinsight');
session_start();
if(!isset($_SESSION['customer_id'])) {
    header("Location: " . $base_url . "/hiveinsight/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['customer_id'] = $_POST['customer_id'];
    $_SESSION['arduino_id'] = $_POST['arduino_id'];

    header("Location: " . $base_url . "/hiveinsight/datachart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="icon" href="styles/icon.ico" type="image/x-icon">
    <link rel="stylesheet" href="styles/styles.css">
</head>

<body>
    <div class="dashboard-container">
        <header>
            <h1>HiveInsight</h1>
            <div class="header-buttons">
                <a href="beehiveconnect.php"><button id="btn">Connect smarthive</button></a>
                <a href="logout.php"><button id="btn">Logout</button></a>
            </div>
        </header>
        <main>
            <section class="hive-list">
                <h2>Smarthive dashboard</h2>
                <ul id="hive-list"></ul>
            </section>

            <section class="hive-data">
            <?php
            $customer_id = $_SESSION['customer_id'];

            include("includes/dashboard.api.php");

                $data = json_decode(GetData($customer_id), true);

                if ($data === NULL) {
                    echo 'Er is een fout opgetreden bij het decoderen van de JSON-gegevens.';
                } else {
                    if (empty($data)) {
                        echo 'Geen gegevens beschikbaar.';
                    } else {
                        echo '<table class="styled-table">';
                        echo '<thead>';
                        echo '<tr>';
                        echo '<th>Name</th>';
                        echo '<th>Location</th>';
                        echo '<th>Latest</th>';
                        echo '<th>Temperature</th>';
                        echo '<th>Humidity</th>';
                        echo '<th></th>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';

                        foreach ($data as $entry) {
                            echo '<tr>';
                            echo '<td>' . $entry['name'] . '</td>';
                            echo '<td>' . $entry['location'] . '</td>';
                            echo '<td>' . $entry['reading_time'] . '</td>';
                            echo '<td>' . $entry['temperature'] . '°C</td>';
                            echo '<td>' . $entry['humidity'] . '%</td>';
                            echo '<td><button id="btn" onclick="redirectToDataChart(' . $customer_id . ', ' . $entry['arduino_id'] . ')">Chart</button></td>';
                            echo '</tr>';
                        }

                        echo '</table>';
                    }
                }
            ?>

                <form id="chartForm" action="" method="post">
                    <input type="hidden" name="customer_id" id="customerIdInput">
                    <input type="hidden" name="reservation_id" id="arduinoIdInput">
                </form>

                <ul id=""></ul>
            </section>
        </main>
    </div>

    <script>
        function redirectToDataChart(customerId, arduinoId) {
            // Vul de verborgen velden in het formulier in
            document.getElementById("customerIdInput").value = customerId;
            document.getElementById("arduinoIdInput").value = arduinoId;

            // Verstuur het formulier
            document.getElementById("chartForm").submit();
        }
    </script>
</body>

</html>
