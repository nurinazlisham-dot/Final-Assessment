<?php
session_start();

include 'database.php';

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
<html>

<head>
    <title>Login - MAHSA Student Grading System</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="container">

        <h1>MAHSA KOLEJ</h1>
        <h2>Student Grading System</h2>

        <?php if ($error != "") { ?>

            <p class="error">
                <?php echo htmlspecialchars($error); ?>
            </p>

        <?php } ?>

        <form action="login.php" method="POST">

            <label>Username</label>
            <input type="text" name="username"
                placeholder="Enter Username" required>

            <label>Password</label>
            <input type="password" name="password"
                placeholder="Enter Password" required>

            <button type="submit">LOGIN</button>

        </form>

        <a href="index.php">Back to Login</a>

    </div>

</body>

</html>