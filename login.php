<?php
session_start();

include 'db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user && $password === $user['password']) {

        $_SESSION['username'] = $user['username'];

        header("Location: index.php");
        exit();

    } else {
        $error = "Invalid Username or Password!";
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Student Grading System</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css?v=7">
</head>

<body>

    <div class="container">

        <div class="login-card">

            <div class="portal-label">✦ STUDENT PORTAL</div>

            <h1>MAHSA KOLEJ</h1>

            <h2>Student Grading System</h2>

            <p class="subtitle">
                Keep your academic records beautifully organised.
            </p>

            <?php if ($error != "") { ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php } ?>

            <form action="login.php" method="POST">

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Enter username" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter password" required>
                </div>

                <button type="submit">LOGIN</button>

            </form>

            <p class="link-text">
                <a href="forgotpassword.php">Forgot password?</a>
            </p>

        </div>

    </div>

</body>
</html>