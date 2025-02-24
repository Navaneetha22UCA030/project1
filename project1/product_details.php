<?php
session_start();
include_once('includes/config.php');

// Check if user is logged in
if (!isset($_SESSION['id']) || strlen($_SESSION['id']) == 0) {
    header('location:logout.php');
    exit;
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$productId = intval($_GET['id']);
$productQuery = mysqli_query($con, "SELECT * FROM products WHERE id='$productId'");
$product = mysqli_fetch_assoc($productQuery);

if (!$product) {
    header("Location: products.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Product Details</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <!-- Custom Styles -->
    <link href="css/styles.css" rel="stylesheet" />

    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
</head>

<body class="sb-nav-fixed">
    <!-- Navbar -->
    <?php include_once('includes/navbar.php'); ?>

    <div id="layoutSidenav">
        <!-- Sidebar -->
        <?php include_once('includes/sidebar.php'); ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container mt-4">
                    <div class="card">
                        <div class="card-header">
                            <h1 class="h2">ZamZam Power Tool Product</h1>
                        </div>
                        <div class="card-body mb-5">
                            <div class="row">
                                <!-- Product Image -->
                                <div class="col-md-6">
                                    <img src="<?php echo htmlspecialchars($product['productImage1']); ?>" class="img-fluid" alt="Product Image">
                                </div>

                                <!-- Product Details -->
                                <div class="col-md-6">
                                    <h2><?php echo htmlspecialchars($product['productName']); ?></h2>
                                    <p class="text-muted"><?php echo htmlspecialchars($product['productDescription']); ?></p>
                                    <p><strong>Company:</strong> <?php echo htmlspecialchars($product['productCompany']); ?></p>
                                    <p><strong>Price:</strong> <span class="text-danger fw-bold">$<?php echo number_format($product['productPrice'], 2); ?></span></p>
                                    <p><strong>Shipping Charge:</strong> $<?php echo number_format($product['shippingCharge'], 2); ?></p>

                                    <?php
                                    $totalAmount = $product['productPrice'] + $product['shippingCharge'];
                                    ?>
                                    <p><strong>Total Price (Including Shipping):</strong> <span class="text-success fw-bold">$<?php echo number_format($totalAmount, 2); ?></span></p>

                                    <p><strong>Availability:</strong> <?php echo htmlspecialchars($product['productAvailability']); ?></p>
                                    <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($product['paymentMethod']); ?></p>

                                    <!-- Action Buttons -->
                                    <a href="shipping.php?id=<?php echo $product['id']; ?>" class="btn btn-success">Buy Now</a>
                                    <a href="products.php" class="btn btn-secondary">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>