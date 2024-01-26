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
    <title>Datachart</title>
    <link rel="icon" href="styles/icon.ico" type="image/x-icon">
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
</head>

<body>
    <div class="dashboard-container">
        <header>
            <h1>HiveInsight</h1>
            <div class="header-buttons">
            <a href="beehiveinfo.php"><button id="btn">Info</button></a>
                <a href="dashboard.php"><button id="btn">Back</button></a>
                <a href="logout.php"><button id="btn">Logout</button></a>
            </div>
        </header>

        <main>
        <section class="hive-list" style="width:900px;">
                <h2>Smarthive data</h2>
                <ul id="hive-list"></ul>
            </section>
            <div>
                <?php
                include("includes/datachart.api.php");

                $data = json_decode(GetData($customer_id, $arduino_id), true);

                $jsonData = json_encode($data);

                if ($data === NULL) {
                    echo '<p style="color: red;"><b>An error occurred while retrieving the data.</b></p>';
                }
                if (empty($data)) {
                    echo '<p style="color: red;"><b>No data available.</b></p>';
                }
                ?>
            </div>

            <div class="chart-list" style="width:900px;">
                <h3 align="center">Last 12 hours data</h3>
                <br /><br />

                <h3 align="center">Temperature</h3>
                <div id="chart1"></div>
                <br /><br />

                <h3 align="center">Humidity</h3>
                <div id="chart2"></div>
                <br /><br />

                <h3 align="center">Air quality</h3>
                <div id="chart3"></div>
                <br /><br />

                <h3 align="center">Motion</h3>
                <div id="chart4"></div>
                <br /><br />
            </div>

            <ul id=""></ul>
        </main>

        <script>
        var data = <?php echo json_encode($data); ?>;
        var dataReverse = <?php echo json_encode(array_reverse($data)); ?>;
        
        Morris.Line({
            element: 'chart1',
            data: data,
            xkey: ['avg_hour'],
            ykeys: ['temperature0', 'temperature1', 'temperature2', 'temperature3'],
            labels: ['Temperature0', 'Temperature1', 'Temperature2', 'Temperature3'],
            hideHover: 'auto',
            lineColors: ['#FADF39'],
            goals: [35],  // Dit tekent een lijn op y=20
            goalStrokeWidth: 2,
            goalLineColors: ['#483C32']
        });

        Morris.Line({
            element: 'chart2',
            data: data,
            xkey: ['avg_hour'],
            ykeys: ['humidity0', 'humidity1', 'humidity2', 'humidity3'],
            labels: ['Humidity0', 'Humidity1', 'Humidity2', 'Humidity3'],
            hideHover: 'auto',
            lineColors: ['#333333'],
            goals: [50],  // Dit tekent een lijn op y=20
            goalStrokeWidth: 2,
            goalLineColors: ['#483C32']
        });

        Morris.Line({
            element: 'chart3',
            data: data,
            xkey: ['avg_hour'],
            ykeys: ['airquality0', 'airquality1', 'airquality2', 'airquality3'],
            labels: ['Air quality0', 'Air quality1', 'Air quality2', 'Air quality3'],
            hideHover: 'auto',
            lineColors: ['#538F53'],
            goals: [1000],
            goalStrokeWidth: 2,
            goalLineColors: ['#483C32']
        });

        Morris.Bar({
            element: 'chart4',
            data: dataReverse,
            xkey: ['avg_hour'],
            ykeys: ['motion0', 'motion1', 'motion2', 'motion3'],
            labels: ['Motion0', 'Motion1', 'Motion2', 'Motion3'],
            hideHover: 'auto',
            barColors: ['#538F53', '#333333', '#BF7839', '#FFE12E'],
        });
</script>
    </body>
</html>
