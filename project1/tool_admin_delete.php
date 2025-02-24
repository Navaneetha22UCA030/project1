<?php
session_start();
include_once('includes/config.php');

if (strlen($_SESSION['adminid'] == 0)) {
    header('location:logout.php');
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project1";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM rental_tools WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }

    $stmt->close();
    $conn->close();
}
?>
