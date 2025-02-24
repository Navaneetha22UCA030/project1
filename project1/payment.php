<?php
session_start();
include_once('includes/config.php');
require('razorpay-php/Razorpay.php'); // Include Razorpay SDK

use Razorpay\Api\Api;

$apiKey = "your_razorpay_key_id";  // Replace with your Razorpay Key ID
$apiSecret = "your_razorpay_secret";  // Replace with your Razorpay Secret Key

if (!isset($_GET['orderId']) || !isset($_GET['amount'])) {
    header("Location: products.php");
    exit();
}

$orderId = intval($_GET['orderId']);
$amount = floatval($_GET['amount']) * 100; // Convert to paise (1 INR = 100 paise)

$api = new Api($apiKey, $apiSecret);

// Create order in Razorpay
$order = $api->order->create([
    'receipt' => "order_" . $orderId,
    'amount' => $amount,
    'currency' => 'INR',
    'payment_capture' => 1
]);

$razorpayOrderId = $order['id'];
$_SESSION['razorpay_order_id'] = $razorpayOrderId;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UPI Payment</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body>
    <h2>Processing Payment...</h2>

    <script>
        var options = {
            "key": "<?php echo $apiKey; ?>",
            "amount": "<?php echo $amount; ?>",
            "currency": "INR",
            "name": "Your Store Name",
            "description": "Order Payment",
            "image": "your-logo-url.png",
            "order_id": "<?php echo $razorpayOrderId; ?>",
            "handler": function (response){
                window.location.href = "payment_success.php?payment_id=" + response.razorpay_payment_id + "&order_id=<?php echo $orderId; ?>";
            },
            "theme": {
                "color": "#3399cc"
            }
        };
        var rzp1 = new Razorpay(options);
        rzp1.open();
    </script>
</body>
</html>
