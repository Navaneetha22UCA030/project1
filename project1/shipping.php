<?php
session_start();
include_once('includes/config.php');

if (strlen($_SESSION['id']) == 0) {
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
            header("Location: place_order.php?orderId=$orderId&amount=$price");
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
    <title>Place Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .order-container {
            max-width: 600px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="order-container">
            <h3 class="text-center">Shipping Details</h3>

            <?php if (isset($_SESSION['error'])) { ?>
                <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php } ?>

            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user['fname']); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($user['contactno']); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Delivery Address</label>
                    <input type="text" name="user_address" class="form-control" required>
                </div>

                <div class="mb-3">
    <label class="form-label">Quantity</label>
    <input type="text" name="quantity" class="form-control" value="1" min="1" readonly required>
</div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">City</label>
                        <input type="text" name="city" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">State</label>
                        <input type="text" name="state" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Zip Code</label>
                        <input type="text" name="zip_code" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Payment Method</label>
                    <select name="paymentMethod" class="form-control" required>
                        <option value="Cash on Delivery">Cash on Delivery</option>
                        <option value="Online Payment" disabled>Online Payment</option>
                    </select>
                </div>

                <button type="submit" name="submit" class="btn btn-primary w-100">Place Order</button>
                <a href="products.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</body>
<script>
function showUPIQRCode() {
    var paymentMethod = document.getElementById("paymentMethod").value;
    var upiDiv = document.getElementById("upiQRCode");
    var upiImg = document.getElementById("upiQR");

    if (paymentMethod === "Online Payment") {
        var upiID = "yourupiid@upi"; // Replace with your UPI ID
        var amount = <?php echo $product['productPrice']; ?>; // Product price
        var upiURL = "upi://pay?pa=" + encodeURIComponent(upiID) + "&pn=YourName&mc=&tid=&tr=&tn=ProductPayment&am=" + amount + "&cu=INR";

        // Generate QR Code using Google Chart API
        upiImg.src = "https://chart.googleapis.com/chart?chs=250x250&cht=qr&chl=" + encodeURIComponent(upiURL);
        upiDiv.classList.remove("d-none"); // Show QR Code
    } else {
        upiDiv.classList.add("d-none"); // Hide QR Code for Cash on Delivery
    }
}
</script>

</html>
