<?php
session_start();
include_once('includes/config.php');

// Secure session check
if (!isset($_SESSION['id']) || strlen($_SESSION['id']) == 0) {
    header('location:logout.php');
    exit;
}

$userId = $_SESSION['id'];

// Fetch user details
$query = $con->prepare("SELECT fname, lname FROM users WHERE id = ?");
$query->bind_param("i", $userId);
$query->execute();
$userResult = $query->get_result();
$user = $userResult->fetch_assoc();

// Fetch user orders with product details
$orderQuery = $con->prepare("SELECT orders.*, products.productName, products.productImage1, products.productPrice, products.shippingCharge 
                             FROM orders 
                             JOIN products ON orders.productId = products.id 
                             WHERE orders.userId = ? 
                             ORDER BY orders.orderDate DESC");
$orderQuery->bind_param("i", $userId);
$orderQuery->execute();
$orderResult = $orderQuery->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>User Dashboard</title>
    <link rel="stylesheet" href="css/styles.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Icon Bootstrap-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
</head>

<body class="sb-nav-fixed">
    <!-- Navbar -->
    <?php include_once('includes/navbar.php'); ?>

    <div id="layoutSidenav">
        <!-- Sidebar -->
        <?php include_once('includes/sidebar.php'); ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container mt-5">

                    <div class="card">
                        <div class="card-header">
                            <h2 class="mb-3">📦 Your Orders</h2>
                        </div>

                        <div class="card-body">
                            <?php if (isset($_SESSION['msg'])) { ?>
                                <div class="alert alert-success">
                                    <?php echo $_SESSION['msg'];
                                    unset($_SESSION['msg']); ?>
                                </div>
                            <?php } ?>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Product Image</th>
                                            <th>Product</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Shipping</th>
                                            <th>Total Price</th>
                                            <th>Payment Method</th>
                                            <th>Status</th>
                                            <th>Order Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $orderResult->fetch_assoc()) { 
                                            $totalPrice = ($row['productPrice'] * $row['quantity']) + $row['shippingCharge'];
                                        ?>
                                            <tr>
                                                <td><?php echo $row['id']; ?></td>
                                                <td>
                                                    <img src="<?php echo htmlspecialchars($row['productImage1']); ?>" class="img-thumbnail" width="80" height="80" alt="Product Image">
                                                </td>
                                                <td><?php echo htmlspecialchars($row['productName']); ?></td>
                                                <td><?php echo $row['quantity']; ?></td>
                                                <td>$<?php echo number_format($row['productPrice'], 2); ?></td>
                                                <td>$<?php echo number_format($row['shippingCharge'], 2); ?></td>
                                                <td><strong class="text-success">$<?php echo number_format($totalPrice, 2); ?></strong></td>
                                                <td><?php echo htmlspecialchars($row['paymentMethod']); ?></td>
                                                <td>
                                                    <span class="badge bg-<?php echo ($row['orderStatus'] == 'Delivered') ? 'success' : 'warning'; ?>">
                                                        <?php echo htmlspecialchars($row['orderStatus']); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo date('d M Y, h:i A', strtotime($row['orderDate'])); ?></td>
                                                <td>
                                                    <a href="order_details.php?orderId=<?php echo $row['id']; ?>" class="btn btn-info btn-sm">View</a>

                                                    <?php if ($row['orderStatus'] !== 'Delivered' && $row['orderStatus'] !== 'Cancelled') { ?>
                                                        <button class="btn btn-danger btn-sm cancel-order" data-orderid="<?php echo $row['id']; ?>">Cancel</button>
                                                    <?php } ?>

                                                    <?php if ($row['orderStatus'] === 'Cancelled') { ?>
                                                        <span class="badge bg-danger">Cancelled</span>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>

                                <a href="products.php" class="btn btn-primary mt-3">Continue Shopping</a>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".cancel-order").forEach(button => {
            button.addEventListener("click", function() {
                let orderId = this.getAttribute("data-orderid");

                if (confirm("Are you sure you want to cancel this order?")) {
                    fetch("cancel_order.php", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/x-www-form-urlencoded"
                            },
                            body: "orderId=" + orderId
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === "success") {
                                alert(data.message);
                                location.reload(); // Refresh page to reflect changes
                            } else {
                                alert(data.message);
                            }
                        })
                        .catch(error => console.error("Error:", error));
                }
            });
        });
    });
</script>

</html>
