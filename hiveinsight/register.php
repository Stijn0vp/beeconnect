<?php
$base_url = 'http://' . $_SERVER['HTTP_HOST'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>User Registration</title>
    <link rel="icon" href="styles/icon.ico" type="image/x-icon">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="styles/input-page.css">
</head>

<body>
    <section>
        <div class="form-box-register">
            <div class="form-value">
                <h2>Register</h2>

                <form id="registrationForm" onsubmit="submitForm(event)">
                    <div class="inputbox">
                        <input type="text" id="username" name="username" required>
                        <label for="username">Username:</label>
                    </div>
                    <div class="inputbox">
                        <input type="email" id="email" name="email" required>
                        <label for="email">E-mail:</label>
                    </div>
                    <div class="inputbox">
                        <input type="text" id="phone_number" name="phone_number" required>
                        <label for="phone_number">Phone number:</label>
                    </div>
                    <div class="inputbox">
                        <input type="password" id="password" name="password" required>
                        <label for="password">Password:</label>
                    </div>

                    <button type="submit" id="btn" name="submit">Register</button>
                    
                    <div class="register">
                        <p>Already have an account? <a onclick="window.location.href='login.php'" id="btn">Log In</a></p>
                    </div>
                    <div class="register">
                        <p><a onclick="window.location.href='https://192.168.159.70/index.html'" id="btn">Back to homepage</a></p>
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
                const response = await fetch(base_url + '/hiveinsight/includes/register.api.php', {
                    method: 'POST',
                    body: formData,
                });

                const result = await response.json();

                if (result.success) {
                    alert('Registration successful!');
                    window.location.href = base_url + '/hiveinsight/login.php';
                } else {
                    alert('Registration failed: ' + result.message);
                }
            } catch (error) {
                console.error('Error during registration:', error);
                alert('An error occurred during registration.');
            }
        }
    </script>
</body>

</html>
