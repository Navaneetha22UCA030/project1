<?php
session_start();
include_once('includes/config.php');

if (strlen($_SESSION['adminid']) == 0) {
    header('location:logout.php');
    exit();
}

$productID = intval($_GET['id']);

// Fetch product details for image deletion
$query = mysqli_query($con, "SELECT * FROM products WHERE id='$productID'");
$product = mysqli_fetch_assoc($query);

// Delete images from the server
unlink($product['productImage1']);
unlink($product['productImage2']);
unlink($product['productImage3']);

// Delete product from database
mysqli_query($con, "DELETE FROM products WHERE id='$productID'");

$_SESSION['msg'] = "Product Deleted Successfully!";
header("Location: insert_poroduct_admin.php");
exit();
?>
