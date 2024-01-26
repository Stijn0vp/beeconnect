<?php
$base_url = 'http://' . $_SERVER['HTTP_HOST'];

ini_set('session.cookie_path', '/hiveinsight');
session_start();
if(!isset($_SESSION['customer_id'])) {
    header("Location: " . $base_url . "/hiveinsight/login.php");
    exit();
}

$customer_id = isset($_SESSION['customer_id']) ? $_SESSION['customer_id'] : null;
$arduino_id = isset($_SESSION['arduino_id']) ? $_SESSION['arduino_id'] : null;
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
                <a href="dashboard.php"><button id="btn">Back</button></a>
                <a href="logout.php"><button id="btn">Logout</button></a>
            </div>
        </header>
        <main>
            <section class="hive-list">
                <h2>Smarthive information</h2>
                <ul id="hive-list"></ul>
            </section>

            <section class="hive-data">
                <?php
                include("includes/beehiveinfo.api.php");

                    $data = json_decode(GetInfo($customer_id, $arduino_id), true);

                    if ($data === NULL) {
                        echo 'Er is een fout opgetreden bij het ophalen van de gegevens.';
                    } else {                
                        if (empty($data)) {
                            echo 'Geen gegevens beschikbaar.';
                        } else {
                            echo '<div>';
                    
                            foreach ($data as $entry) {
                                echo '<div>';
                                echo '<strong>Name:</strong> ' . $entry['smarthive_name'] . '<br>';
                                echo '<strong>Location:</strong> ' . $entry['smarthive_location'] . '<br>';
                                echo '<strong>Arduino id:</strong> ' . $entry['arduino_id'] . '<br>';
                                echo '<strong>Connect ID:</strong> ' . $entry['ConnectID'] . '<br>';
                                echo '<strong>status:</strong> ' . $entry['status'] . '<br>';

                                echo '</div>';
                            }
                    
                            echo '</div>';
                        }
                    }
                ?>

                <button type="button" id="btn" name="submit" onclick="executeAction()">DELETE SMARTHIVE</button>

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

    <script>
        const base_url = '<?php echo $base_url; ?>';

        async function executeAction() {
            try {
                const response = await fetch(base_url + '/hiveinsight/includes/beehiveinfo.api.php', {
                    method: 'POST',
                });

                const result = await response.json();

                if (result.success) {
                    alert('Actie succesvol uitgevoerd!');
                    window.location.href = base_url + '/hiveinsight/dashboard.php';
                } else {
                    alert('Fout bij het uitvoeren van de actie: ' + result.message);
                }
            } catch (error) {
                console.error('Fout bij het uitvoeren van de actie:', error);
                alert('Er is een fout opgetreden bij het uitvoeren van de actie', error);
            }
        }
    </script>
</body>

</html>
