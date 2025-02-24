<?php
session_start();
include_once('includes/config.php');

if (!isset($_SESSION['id']) || strlen($_SESSION['id']) == 0) {
    header('location:logout.php');
    exit;
}

$userId = $_SESSION['id'];
$productId = intval($_GET['id']);

// Fetch user details
$userQuery = mysqli_query($con, "SELECT fname, contactno FROM users WHERE id='$userId'");
$user = mysqli_fetch_assoc($userQuery);

// Fetch product details
$productQuery = mysqli_query($con, "SELECT * FROM products WHERE id='$productId'");
$product = mysqli_fetch_assoc($productQuery);

if (!$product) {
    header("Location: products.php");
    exit();
}

// Handle form submission
if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $user_address = mysqli_real_escape_string($con, $_POST['user_address']);
    $city = mysqli_real_escape_string($con, $_POST['city']);
    $state = mysqli_real_escape_string($con, $_POST['state']);
    $zip_code = mysqli_real_escape_string($con, $_POST['zip_code']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $paymentMethod = mysqli_real_escape_string($con, $_POST['paymentMethod']);
    $quantity = intval($_POST['quantity']);
    
    $price = $product['productPrice'] * $quantity; // Calculate total price

    // Insert order into database
    $query = "INSERT INTO orders (userId, productId, quantity, paymentMethod, orderDate, orderStatus, user_address, city, state, zip_code)
              VALUES ('$userId', '$productId', '$quantity', '$paymentMethod', NOW(), 'Pending', '$user_address', '$city', '$state', '$zip_code')";

    if (mysqli_query($con, $query)) {
        $orderId = mysqli_insert_id($con);

        if ($paymentMethod == "Online Payment") {
            header("Location: payment.php?orderId=$orderId&amount=$price");
            exit();
        } else {
            $_SESSION['msg'] = "Your order has been placed successfully!";
            echo "<script>alert('Your Order Placed Successfully'); window.location='products.php';</script>";
            exit();
        }
    } else {
        $_SESSION['error'] = "Something went wrong. Try again!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UPI Payment</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5 text-center">
        <h2>Scan to Pay</h2>
        <p>Order ID: <?php echo $orderId; ?></p>
        <p>Amount: ₹<?php echo $amount; ?></p>
        <div id="qrcode"></div>
        <br>
        <a href="<?php echo $upi_url; ?>" class="btn btn-success">Pay Now</a>
    </div>

    <script>
        var upi_url = "<?php echo $upi_url; ?>";
        new QRCode(document.getElementById("qrcode"), {
            text: upi_url,
            width: 200,
            height: 200
        });
    </script>
</body>
</html>
