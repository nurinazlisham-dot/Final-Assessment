<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}

require_once __DIR__ . '/../db.php';

$id = (int)($_GET['id'] ?? 0);

$sql = "SELECT * FROM students WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$student = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$student) {
    die("Student record not found!");
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Student</title>

    <link rel="stylesheet" href="../style.css">
</head>

<body>

    <div class="container">

        <h1>Student Grading System</h1>
        <h2></h2>
        <h3>Edit Student Information</h3>

        <form action="index.php" method="POST">

            <input type="hidden" name="id"
                value="<?php echo $student['id']; ?>">

            <label>Student Name</label>
            <input type="text" name="student_name"
                value="<?php echo htmlspecialchars($student['student_name']); ?>" required>

            <label>Matric Number</label>
            <input type="text" name="matric_no"
                value="<?php echo htmlspecialchars($student['matric_no']); ?>" required>

            <label>Subject Name</label>
            <input type="text" name="subject_name"
                value="<?php echo htmlspecialchars($student['subject_name']); ?>" required>

            <label>Subject Code</label>
            <input type="text" name="subject_code"
                value="<?php echo htmlspecialchars($student['subject_code']); ?>" required>

            <label>Test 1 (20%)</label>
            <input type="number" name="test1"
                min="0" max="100" step="0.01"
                value="<?php echo $student['test1']; ?>" required>

            <label>Test 2 (30%)</label>
            <input type="number" name="test2"
                min="0" max="100" step="0.01"
                value="<?php echo $student['test2']; ?>" required>

            <label>Final Examination (50%)</label>
            <input type="number" name="final_exam"
                min="0" max="100" step="0.01"
                value="<?php echo $student['final_exam']; ?>" required>

            <br><br>

            <button type="submit">UPDATE STUDENT</button>

        </form>

        <a href="index.php">Back to Student Records</a>

        <br><br>

        <a href="../index.php">Back to Dashboard</a>

    </div>

</body>

</html>