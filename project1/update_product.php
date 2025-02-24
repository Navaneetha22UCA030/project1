<?php
include_once('includes/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productId = intval($_POST['productId']);
    $paymentMethod = $_POST['paymentMethod'];

    mysqli_query($con, "UPDATE products SET paymentMethod='$paymentMethod' WHERE id='$productId'");
    
    header("Location: admin_edit_product.php?id=$productId&success=1");
    exit();
}
?>
