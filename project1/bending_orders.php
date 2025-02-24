<?php
session_start();
include_once('includes/config.php');

if (strlen($_SESSION['id']) == 0) {
    header('location:logout.php');
    exit;
}

// Fetch pending orders
$query = "SELECT orders.*, users.fname, users.contactno, products.productName 
          FROM orders 
          JOIN users ON orders.userId = users.id 
          JOIN products ON orders.productId = products.id
          WHERE orders.orderStatus = 'Pending' 
          ORDER BY orders.orderDate DESC";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pending Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Pending Orders</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Contact</th>
                    <th>Product</th>
                    <th>Payment Method</th>
                    <th>Status</th>
                    <th>Order Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['fname']; ?></td>
                        <td><?php echo $row['contactno']; ?></td>
                        <td><?php echo $row['productName']; ?></td>
                        <td><?php echo $row['paymentMethod']; ?></td>
                        <td><?php echo $row['orderStatus']; ?></td>
                        <td><?php echo $row['orderDate']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
