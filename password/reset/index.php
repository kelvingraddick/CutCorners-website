<?php
// Check if the token is provided in the URL
if (isset($_GET['token'])) {
    $token = $_GET['token'];
} else {
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <img class="logo" src="https://www.cutcornersapp.com/assets/images/logo.png" />
    <form id="form">
        <div class="title">Reset Password</div>

        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" placeholder="Type here.." required>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Type here.." required>

        <!-- Hidden input field to pass the token -->
        <input type="hidden" name="token" value="<?php echo $token; ?>">

        <input type="submit" value="Reset Password">
    </form>
    <script>
        var form = document.getElementById("form");
        form.addEventListener("submit", function (event) {
            event.preventDefault();

            // validate
            var newPassword = document.getElementById('new_password').value;
            var confirmPassword = document.getElementById('confirm_password').value;
            if (!newPassword || newPassword.length < 8 || newPassword.length > 20) {
                alert('You must enter a new password between 8 and 20 characters! Please try again.');
                return;
            }
            if (newPassword !== confirmPassword) {
                alert('Passwords must match! Please try again.');
                return;
            }

            // submit
            fetch('https://www.cutcornersapp.com/api/user/password/reset/save.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    new_password: newPassword,
                    token: '<?php echo $token; ?>',
                }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location = 'https://www.cutcornersapp.com/password/reset/success.php';
                } else {
                    var errorCode = data && data.error_code;
                    if (errorCode === 6) {
                        alert('There was reset password link was invalid! Please try again.');
                    } else {
                        alert('There was a server error! Please try again.');
                    }
                }
            })
            .catch(error => {
                console.error('Error: ', error);
                alert('There was a server error! Please try again.');
            });
        }, true);
    </script>
</body>
</html>