<?php
session_start();
include_once('includes/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_id'])) {
    $orderId = intval($_POST['order_id']);

    // Check if the order exists
    $checkOrder = $con->prepare("SELECT id FROM orders WHERE id = ?");
    $checkOrder->bind_param("i", $orderId);
    $checkOrder->execute();
    $result = $checkOrder->get_result();

    if ($result->num_rows === 0) {
        echo "error: Order not found";
        exit;
    }
    $checkOrder->close();

    // Update order status to Delivered
    $stmt = $con->prepare("UPDATE orders SET orderStatus = 'Delivered' WHERE id = ?");
    $stmt->bind_param("i", $orderId);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error: " . $stmt->error; // Output error message for debugging
    }
    
    $stmt->close();
    $con->close();
} else {
    echo "error: Invalid request";
}
?>
