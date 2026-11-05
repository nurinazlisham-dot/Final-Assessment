<?php
session_start();

// Mesti login dulu
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

// Pastikan ada id dan id tu nombor sahaja
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = (int) $_GET['id'];

// Padam rekod guna prepared statement
$stmt = $conn->prepare("DELETE FROM results WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

// Kembali ke senarai rekod
header("Location: index.php");
exit();
?>