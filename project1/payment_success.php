<?php
session_start();
include_once('includes/config.php');

if (!isset($_GET['payment_id']) || !isset($_GET['order_id'])) {
    header("Location: dashboard.php");
    exit();
}

$payment_id = mysqli_real_escape_string($con, $_GET['payment_id']);
$order_id = intval($_GET['order_id']);

// Update order status
$updateQuery = "UPDATE orders SET orderStatus='Paid', paymentId='$payment_id' WHERE id='$order_id'";
if (mysqli_query($con, $updateQuery)) {
    $_SESSION['msg'] = "Payment successful! Order confirmed.";
} else {
    $_SESSION['error'] = "Payment verified but failed to update order.";
}

header("Location: dashboard.php");
exit();
?>
