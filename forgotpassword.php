<?php
include 'db.php';

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $error = "Passwords do not match!";
    } elseif (strlen($new_password) < 6) {
        $error = "Password must be at least 6 characters!";
    } else {
        $sql = "SELECT * FROM users WHERE username = ? AND email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $username, $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if ($user) {
            $update = "UPDATE users SET password = ? WHERE username = ?";
            $stmt2 = mysqli_prepare($conn, $update);
            mysqli_stmt_bind_param($stmt2, "ss", $new_password, $username);
            mysqli_stmt_execute($stmt2);
            mysqli_stmt_close($stmt2);

            $success = "Password updated! You can login now.";
        } else {
            $error = "Username and email do not match!";
        }
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Forgot Password - Student Grading System</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css?v=7">
</head>

<body>

    <div class="container">

        <div class="login-card">

            <div class="portal-label">✦ STUDENT PORTAL</div>

            <h1>MAHSA KOLEJ</h1>

            <h2>Reset Password</h2>

            <p class="subtitle">
                Enter your username and email to set a new password.
            </p>

            <?php if ($error != "") { ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php } ?>

            <?php if ($success != "") { ?>
                <p class="success"><?php echo htmlspecialchars($success); ?></p>
            <?php } ?>

            <form action="forgotpassword.php" method="POST">

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Enter username" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter email" required>
                </div>

                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" placeholder="Enter new password" required>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm new password" required>
                </div>

                <button type="submit">RESET PASSWORD</button>

            </form>

            <p class="link-text">
                <a href="login.php">← Back to Login</a>
            </p>

        </div>

    </div>

</body>
</html>