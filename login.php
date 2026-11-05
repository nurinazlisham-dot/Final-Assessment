
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

        header("Location: dashboard.php");
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

    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="container">

        <div class="login-card">

            <h1>Student Grading System</h1>

            <p class="subtitle">Welcome back! Please log in.</p>

            <?php if ($error != "") { ?>
                <p class="error">
                    <?php echo htmlspecialchars($error); ?>
                </p>
            <?php } ?>

            <form action="login.php" method="POST">

                <label for="username">Username</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    placeholder="Enter Username"
                    required
                >

                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter Password"
                    required
                >

                <button type="submit">LOGIN</button>

            </form>

            <a href="index.php" class="back-link">Back to Login</a>

        </div>

    </div>

</body>
</html>
