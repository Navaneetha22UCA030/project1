<?php
session_start();
include_once('includes/config.php');

if (!isset($_SESSION['adminid'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['orderId']) && isset($_POST['status'])) {
    $orderId = intval($_POST['orderId']);
    $status = $_POST['status'];

    // Update order status in the database
    $query = "UPDATE orders SET orderStatus = ? WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("si", $status, $orderId);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Order marked as Delivered"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update order"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}
?>
    