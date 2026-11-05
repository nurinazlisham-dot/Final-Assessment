
<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}

require_once __DIR__ . '/../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = (int)($_POST['id'] ?? 0);

    if ($id <= 0) {
        die("Invalid student ID.");
    }

    $sql = "DELETE FROM students WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            header("Location: index.php?deleted=1");
            exit();
        } else {
            echo "Student record not found!";
        }
    } else {
        echo "Delete failed: " . mysqli_stmt_error($stmt);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    echo "Invalid request.";
}
?>