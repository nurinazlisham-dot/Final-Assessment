<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php';

$message = "";

if (isset($_POST['submit'])) {

    $student_name = trim($_POST['student_name']);
    $matric_no = trim($_POST['matric_no']);
    $subject_name = trim($_POST['subject_name']);
    $subject_code = trim($_POST['subject_code']);

    $test1 = $_POST['test1'];
    $test2 = $_POST['test2'];
    $final_exam = $_POST['final_exam'];

    // Validate marks
    if (
        $test1 < 0 || $test1 > 100 ||
        $test2 < 0 || $test2 > 100 ||
        $final_exam < 0 || $final_exam > 100
    ) {
        $message = "Marks must be between 0 and 100.";

    } else {

        // Calculate weighted total
        $total_marks = ($test1 * 0.20)
                     + ($test2 * 0.30)
                     + ($final_exam * 0.50);

        // Calculate grade
        if ($total_marks >= 80) {
            $grade = "A";
        } elseif ($total_marks >= 65) {
            $grade = "B";
        } elseif ($total_marks >= 50) {
            $grade = "C";
        } elseif ($total_marks >= 40) {
            $grade = "D";
        } else {
            $grade = "E";
        }

        // Insert data into database
        $stmt = $conn->prepare(
            "INSERT INTO results
            (student_name, matric_no, subject_name, subject_code,
            test1, test2, final_exam, total_marks, grade)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "ssssdddds",
            $student_name,
            $matric_no,
            $subject_name,
            $subject_code,
            $test1,
            $test2,
            $final_exam,
            $total_marks,
            $grade
        );

        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            $message = "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Student Result</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 40px 20px;

            font-family: Georgia, 'Times New Roman', serif;
            color: #624A72;

            background: linear-gradient(
                135deg,
                #C8B6E2,
                #F3CBDD,
                #F8F4F0
            );

            min-height: 100vh;
        }

        /* Main container */
        .container {
            max-width: 490px;
            margin: auto;
            padding: 28px 30px;

            background: rgba(248, 244, 240, 0.95);

            border: 1px solid #E4D5EA;
            border-radius: 22px;

            box-shadow: 0 12px 35px
            rgba(98, 74, 114, 0.12);
        }

        /* Header */
        .form-heading {
            text-align: center;
            margin-bottom: 25px;
        }

        .tag {
            display: inline-block;

            font-family: Arial, sans-serif;
            font-size: 10px;
            letter-spacing: 2px;

            background: #F3CBDD;
            color: #624A72;

            padding: 7px 13px;
            border-radius: 20px;
        }

        h1 {
            font-size: 26px;
            font-weight: normal;

            margin: 15px 0 8px;
        }

        .form-heading p {
            font-size: 13px;
            color: #A98CC3;
            margin: 0;
        }

        /* Form layout */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 13px;
        }

        .field.full {
            grid-column: span 2;
        }

        label {
            display: block;

            font-size: 13px;
            margin-bottom: 6px;
        }

        label span {
            color: #A98CC3;
        }

        /* Input fields */
        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 11px 13px;

            border: 1px solid #E3D5EA;
            border-radius: 12px;

            background: #FFFFFF;
            color: #624A72;

            font-family: Arial, sans-serif;
            font-size: 12px;

            transition: 0.2s;
        }

        input::placeholder {
            color: #B9A9C4;
        }

        input:focus {
            outline: none;

            border-color: #A98CC3;

            box-shadow: 0 0 0 3px
            rgba(200, 182, 226, 0.25);
        }

        /* Submit button */
        input[type="submit"] {
            width: 100%;
            margin-top: 22px;
            padding: 13px;

            background: #624A72;
            color: #F8F4F0;

            border: none;
            border-radius: 12px;

            font-family: Georgia, serif;
            font-size: 14px;

            cursor: pointer;
            transition: 0.25s;
        }

        input[type="submit"]:hover {
            background: #A98CC3;

            transform: translateY(-2px);

            box-shadow: 0 5px 15px
            rgba(98, 74, 114, 0.18);
        }

        /* Back link */
        .back {
            display: block;
            text-align: center;

            margin-top: 18px;

            color: #A98CC3;
            font-size: 13px;

            text-decoration: none;
        }

        .back:hover {
            color: #624A72;
        }

        /* Message */
        .message {
            text-align: center;
            font-size: 13px;
            margin-top: 12px;
        }

        /* Mobile responsive */
        @media (max-width: 480px) {

            .container {
                padding: 22px 18px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .field.full {
                grid-column: span 1;
            }
        }

    </style>
</head>

<body>

    <div class="container">

        <div class="form-heading">

            <span class="tag">✦ STUDENT PORTAL</span>

            <h1>Student Grading System</h1>

            <p>Keep your academic records beautifully organised.</p>

        </div>

        <form method="POST">

            <div class="form-grid">

                <div class="field full">
                    <label>Student Name</label>

                    <input type="text"
                           name="student_name"
                           placeholder="Enter student name"
                           required>
                </div>

                <div class="field">
                    <label>Matric Number</label>

                    <input type="text"
                           name="matric_no"
                           placeholder="Enter matric number"
                           required>
                </div>

                <div class="field">
                    <label>Subject Code</label>

                    <input type="text"
                           name="subject_code"
                           placeholder="Enter subject code"
                           required>
                </div>

                <div class="field full">
                    <label>Subject Name</label>

                    <input type="text"
                           name="subject_name"
                           placeholder="Enter subject name"
                           required>
                </div>

                <div class="field">
                    <label>Test 1 <span>(20%)</span></label>

                    <input type="number"
                           name="test1"
                           min="0"
                           max="100"
                           step="0.01"
                           placeholder="0 - 100"
                           required>
                </div>

                <div class="field">
                    <label>Test 2 <span>(30%)</span></label>

                    <input type="number"
                           name="test2"
                           min="0"
                           max="100"
                           step="0.01"
                           placeholder="0 - 100"
                           required>
                </div>

                <div class="field full">
                    <label>Final Exam <span>(50%)</span></label>

                    <input type="number"
                           name="final_exam"
                           min="0"
                           max="100"
                           step="0.01"
                           placeholder="0 - 100"
                           required>
                </div>

            </div>

            <input type="submit"
                   name="submit"
                   value="Save Student Result">

        </form>

        <div class="message">
            <?php echo htmlspecialchars($message); ?>
        </div>

        <a href="index.php" class="back">
            ← Back to Records
        </a>

    </div>

</body>
</html>