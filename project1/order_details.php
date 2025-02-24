<?php
session_start();
include_once('includes/config.php');

if (!isset($_SESSION['id']) || strlen($_SESSION['id']) == 0) {
    header('location:logout.php');
    exit;
}

// Validate orderId from URL
if (!isset($_GET['orderId']) || !is_numeric($_GET['orderId'])) {
    header("location:dashboard.php");
    exit;
}

$orderId = intval($_GET['orderId']);
$userId = $_SESSION['id'];

// Fetch order details
$query = $con->prepare("SELECT orders.*, products.productName, products.productImage1, 
                                products.productDescription, products.productPrice
                         FROM orders 
                         JOIN products ON orders.productId = products.id 
                         WHERE orders.id = ? AND orders.userId = ?");
$query->bind_param("ii", $orderId, $userId);
$query->execute();
$orderResult = $query->get_result();

if ($orderResult->num_rows == 0) {
    header("location:dashboard.php");
    exit;
}

$order = $orderResult->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order Details</title>
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include_once('includes/navbar.php'); ?>
    
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h2>Order Details</h2>
            </div>
            <div class="card-body">
                <h4 class="mb-3"><?php echo htmlspecialchars($order['productName']); ?></h4>
                <img src="product_images/<?php echo htmlspecialchars($order['productImage1']); ?>" alt="Product Image" class="img-fluid mb-3" style="max-width: 300px;">

                <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($order['productDescription'])); ?></p>
                <p><strong>Price:</strong> ₹<?php echo number_format($order['productPrice'], 2); ?></p>
                <p><strong>Quantity:</strong> <?php echo $order['quantity']; ?></p>
                <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($order['paymentMethod']); ?></p>
                <p><strong>Status:</strong> 
                    <span class="badge bg-<?php echo ($order['orderStatus'] == 'Delivered') ? 'success' : 'warning'; ?>">
                        <?php echo htmlspecialchars($order['orderStatus']); ?>
                    </span>
                </p>
                <p><strong>Order Date:</strong> <?php echo date('d M Y, h:i A', strtotime($order['orderDate'])); ?></p>

                <a href="welcome.php    " class="btn btn-secondary">Back to Orders</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
