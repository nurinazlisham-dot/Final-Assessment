<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id           = (int) $_POST['id'];
    $student_name = trim($_POST['student_name']);
    $matric_no    = trim($_POST['matric_no']);
    $subject_name = trim($_POST['subject_name']);
    $test1        = (float) $_POST['test1'];
    $test2        = (float) $_POST['test2'];
    $final_exam   = (float) $_POST['final_exam'];

    if ($test1 < 0 || $test1 > 20 || $test2 < 0 || $test2 > 30 || $final_exam < 0 || $final_exam > 50) {
        $error = "Marks out of range! (Test 1: 0-20, Test 2: 0-30, Final: 0-50)";
    } else {
        $total = $test1 + $test2 + $final_exam;

        if ($total >= 80)      { $grade = "A"; }
        elseif ($total >= 70)  { $grade = "B"; }
        elseif ($total >= 60)  { $grade = "C"; }
        elseif ($total >= 50)  { $grade = "D"; }
        else                   { $grade = "E"; }

        $sql = "UPDATE results
                SET student_name = ?, matric_no = ?, subject_name = ?,
                    test1 = ?, test2 = ?, final_exam = ?, total_marks = ?, grade = ?
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssddddsi", $student_name, $matric_no, $subject_name,
                          $test1, $test2, $final_exam, $total, $grade, $id);
        $stmt->execute();
        $stmt->close();

        header("Location: index.php");
        exit();
    }
} else {
    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
}

// ambil data pelajar
$stmt = $conn->prepare("SELECT * FROM results WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$row) {
    header("Location: index.php");
    exit();
}

// kalau ada error masa submit, kekalkan apa yang user taip
if ($error != "") {
    $row['student_name'] = $student_name;
    $row['matric_no']    = $matric_no;
    $row['subject_name'] = $subject_name;
    $row['test1']        = $test1;
    $row['test2']        = $test2;
    $row['final_exam']   = $final_exam;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Result - Student Grading System</title>

    <link rel="stylesheet" href="style.css?v=10">
</head>

<body>

    <a href="logout.php" class="logout-btn logout-fixed">Logout</a>

    <div class="container">
        <div class="login-card">

            <div class="portal-label">✦ STUDENT PORTAL</div>

            <h1>MAHSA KOLEJ</h1>
            <h2>Edit Result</h2>

            <p class="subtitle">Update the student's marks below.</p>

            <?php if ($error != "") { ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php } ?>

            <form action="edit.php" method="POST">

                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

                <div class="form-group">
                    <label for="student_name">Student Name</label>
                    <input type="text" id="student_name" name="student_name"
                           value="<?php echo htmlspecialchars($row['student_name']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="matric_no">Matric No.</label>
                    <input type="text" id="matric_no" name="matric_no"
                           value="<?php echo htmlspecialchars($row['matric_no']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="subject_name">Subject</label>
                    <input type="text" id="subject_name" name="subject_name"
                           value="<?php echo htmlspecialchars($row['subject_name']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="test1">Test 1 (20)</label>
                    <input type="number" id="test1" name="test1" min="0" max="20" step="0.01"
                           value="<?php echo htmlspecialchars($row['test1']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="test2">Test 2 (30)</label>
                    <input type="number" id="test2" name="test2" min="0" max="30" step="0.01"
                           value="<?php echo htmlspecialchars($row['test2']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="final_exam">Final (50)</label>
                    <input type="number" id="final_exam" name="final_exam" min="0" max="50" step="0.01"
                           value="<?php echo htmlspecialchars($row['final_exam']); ?>" required>
                </div>

                <button type="submit">SAVE CHANGES</button>

            </form>

            <p class="link-text">
                <a href="index.php">← Back to Student Records</a>
            </p>

        </div>
    </div>

</body>
</html>