<?php
$base_url = 'http://' . $_SERVER['HTTP_HOST'];

ini_set('session.cookie_path', '/hiveinsight');
session_start();
if(!isset($_SESSION['customer_id'])) {
    header("Location: " . $base_url . "/hiveinsight/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Connect Smarthive</title>
    <link rel="stylesheet" href="styles/input-page.css">
</head>

<body>
    <section>
        <div class="form-box-login">
            <div class="form-value">
                <h2>Connect Smarthive</h2>
                <form id="registrationForm" onsubmit="submitForm(event)">
                    <div class="inputbox">
                        <input type="text" id="name" name="name" required>
                        <label for="name">Beehive name</label>
                    </div>
                    <div class="inputbox">
                        <input type="text" id="location" name="location" required>
                        <label for="location">Beehive location</label>
                    </div>

                    <input type="hidden" id="customer_id" name="customer_id" value="<?php echo $_SESSION['customer_id']; ?>">

                    <button type="submit" id="btn" name="submit">Create</button>
                    
                    <div class="register">
                        <p><a onclick="window.location.href='dashboard.php'" id="btn">Go Back</a></p>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script>
        const base_url = '<?php echo $base_url; ?>';

        async function submitForm(event) {
            event.preventDefault();

            const form = document.getElementById('registrationForm');
            const formData = new FormData(form);

            try {
                const response = await fetch(base_url + '/hiveinsight/includes/beehiveconnect.api.php', {
                    method: 'POST',
                    body: formData,
                });

                const result = await response.json();

                if (result.success) {
                    alert('Registratie succesvol! ConnectID: ' + result.ConnectID);
                    window.location.href = base_url + '/hiveinsight/dashboard.php';
                } else {
                    alert('Registratie mislukt: ' + result.message);
                }
            } catch (error) {
                console.error('Fout bij registreren:', error);
                alert('Er is een fout opgetreden bij het registreren', error);
            }
        }
    </script>

</body>

</html>
