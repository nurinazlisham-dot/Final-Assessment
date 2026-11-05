<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : "";

if ($search != "") {
    $like = "%" . $search . "%";
    $stmt = $conn->prepare("SELECT * FROM results
                            WHERE student_name LIKE ? OR matric_no LIKE ? OR subject_name LIKE ?");
    $stmt->bind_param("sss", $like, $like, $like);
} else {
    $stmt = $conn->prepare("SELECT * FROM results");
}

$stmt->execute();
$results = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Grading System</title>

    <link rel="stylesheet" href="style.css?v=15">

    <style>
        /* ===== STYLE KHAS PAGE INDEX (jalan walaupun style.css lama) ===== */
        .container {
            align-items: flex-start;
            padding-top: 70px;   /* turunkan kotak beige dari butang Logout */
        }

        /* kotak beige LEBAR */
        .login-card.wide-card {
            width: 96%;
            max-width: 1400px;
            padding: 30px 24px;
            margin: 0 auto;
        }

        .top-actions { margin-top: 4px; }

        .btn.btn-inline,
        button.btn-inline {
            display: inline-block;
            width: auto;
            padding: 11px 26px;
        }

        .search-form {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin: 18px 0 6px;
            flex-wrap: wrap;
        }

        .search-form input {
            flex: 1;
            min-width: 220px;
            max-width: 420px;
            width: auto;
        }

        .search-form button {
            display: inline-block;
            width: auto;
            margin-top: 0;
            padding: 12px 26px;
        }

        /* table dalam kotak beige, border kemas */
        .table-wrap {
            width: 100%;
            overflow-x: auto;
            margin-top: 22px;
            border-radius: 14px;
            background-color: #FFFFFF;
            box-shadow: 0 6px 20px rgba(98, 74, 114, 0.10);
        }

        .marks-table {
            width: 100%;
            min-width: 1000px;
            table-layout: fixed;
            border-collapse: collapse;
            border: 2px solid #624A72;
            font-family: Arial, sans-serif;
            font-size: 13px;
            color: #624A72;
        }

        .marks-table th,
        .marks-table td {
            border: 1px solid #A98CC3;
            padding: 12px 8px;
            text-align: center;
            vertical-align: middle;
            overflow-wrap: anywhere;
        }

        .marks-table th {
            background-color: #F3CBDD;
            font-family: Georgia, serif;
            font-weight: bold;
            border-bottom: 2px solid #624A72;
        }

        .marks-table th:nth-child(1)  { width: 5%;  }
        .marks-table th:nth-child(2)  { width: 16%; }
        .marks-table th:nth-child(3)  { width: 11%; }
        .marks-table th:nth-child(4)  { width: 18%; }
        .marks-table th:nth-child(5)  { width: 7%;  }
        .marks-table th:nth-child(6)  { width: 7%;  }
        .marks-table th:nth-child(7)  { width: 7%;  }
        .marks-table th:nth-child(8)  { width: 7%;  }
        .marks-table th:nth-child(9)  { width: 6%;  }
        .marks-table th:nth-child(10) { width: 16%; }

        .marks-table td:nth-child(2),
        .marks-table td:nth-child(4) { text-align: left; }

        .marks-table tbody tr:nth-child(even) td { background-color: #FBF8FC; }
        .marks-table tbody tr:hover td { background-color: #F3ECF8; }

        .marks-table td a {
            display: inline-block;
            padding: 6px 14px;
            margin: 2px;
            border-radius: 8px;
            font-size: 12px;
            text-decoration: none;
            white-space: nowrap;
        }

        .marks-table td a.btn-edit   { background-color: #EDE3F5; color: #624A72; }
        .marks-table td a.btn-edit:hover   { background-color: #A98CC3; color: #fff; }
        .marks-table td a.btn-delete { background-color: #FBE1E8; color: #A33A5B; }
        .marks-table td a.btn-delete:hover { background-color: #A33A5B; color: #fff; }
    </style>
</head>

<body>

    <a href="logout.php" class="logout-btn logout-fixed" onclick="return confirm('Are you sure you want to log out?');">Logout</a>

    <div class="container">
        <div class="login-card wide-card">

            <div class="portal-label">✦ STUDENT PORTAL</div>

            <h1>MAHSA KOLEJ</h1>
            <h2>Student Records</h2>

            <p class="subtitle">
                Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!
            </p>

            <div class="top-actions">
                <a href="add.php" class="btn btn-inline">+ Add Result</a>
            </div>

            <form action="index.php" method="GET" class="search-form">
                <input type="text" name="search"
                       placeholder="Student name, matric number or subject"
                       value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn-inline">Search</button>
                <a href="index.php" class="show-all">Show All</a>
            </form>

            <div class="table-wrap">
                <table class="marks-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Student Name</th>
                            <th>Matric no.</th>
                            <th>Subject</th>
                            <th>Test 1 (20)</th>
                            <th>Test 2 (30)</th>
                            <th>Final (50)</th>
                            <th>Total (100)</th>
                            <th>Grade</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $no = 1;
                    while ($row = $results->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $no . "</td>";
                        echo "<td>" . htmlspecialchars($row['student_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['matric_no']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['subject_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['test1']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['test2']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['final_exam']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['total_marks']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['grade']) . "</td>";
                        echo "<td>"
                           . "<a class='btn-edit' href='edit.php?id=" . $row['id'] . "'>Edit</a>"
                           . "<a class='btn-delete' href='delete.php?id=" . $row['id'] . "' onclick=\"return confirm('Delete this record?');\">Delete</a>"
                           . "</td>";
                        echo "</tr>";
                        $no++;
                    }

                    if ($no == 1) {
                        echo "<tr><td colspan='10'>No records found.</td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</body>
</html>