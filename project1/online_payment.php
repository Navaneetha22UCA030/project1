<?php
session_start();
include_once('includes/config.php');

if (!isset($_GET['productId']) || !isset($_GET['price'])) {
    header("Location: products.php");
    exit();
}

$productId = intval($_GET['productId']);
$price = floatval($_GET['price']);

echo "<h2>Redirecting to Online Payment for Product ID: $productId</h2>";
echo "<p>Amount: $$price</p>";

echo "<a href='https://www.paypal.com/checkout' class='btn btn-primary'>Proceed to Payment</a>";
?>
