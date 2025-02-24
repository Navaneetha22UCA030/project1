<?php
session_start();
include_once('includes/config.php');

// Check if user is logged in
if (!isset($_SESSION['id']) || strlen($_SESSION['id']) == 0) {
    echo json_encode(["status" => "error", "message" => "Unauthorized access."]);
    exit;
}

$userId = $_SESSION['id'];

// Validate POST request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['orderId']) && is_numeric($_POST['orderId'])) {
    $orderId = intval($_POST['orderId']);

    // Check if the order belongs to the logged-in user and is not already delivered/cancelled
    $checkQuery = $con->prepare("SELECT orderStatus FROM orders WHERE id = ? AND userId = ?");
    $checkQuery->bind_param("ii", $orderId, $userId);
    $checkQuery->execute();
    $result = $checkQuery->get_result();

    if ($result->num_rows == 0) {
        echo json_encode(["status" => "error", "message" => "Invalid order or access denied."]);
        exit;
    }

    $order = $result->fetch_assoc();
    if ($order['orderStatus'] === 'Delivered' || $order['orderStatus'] === 'Cancelled') {
        echo json_encode(["status" => "error", "message" => "Order cannot be cancelled."]);
        exit;
    }

    // Update order status to 'Cancelled'
    $updateQuery = $con->prepare("UPDATE orders SET orderStatus = 'Cancelled' WHERE id = ?");
    $updateQuery->bind_param("i", $orderId);
    if ($updateQuery->execute()) {
        echo json_encode(["status" => "success", "message" => "Order cancelled successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to cancel order. Try again later."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}
?>
