<?php
session_start();
include_once('includes/config.php');

if (strlen($_SESSION['adminid']) == 0) {
    header('location:logout.php');
    exit;
}

// Fetch today's orders with product details
$query = "SELECT orders.*, users.fname, users.contactno, 
                 products.productName, products.productImage1, 
                 products.productPrice, products.shippingCharge, 
                 orders.user_address, orders.city, orders.state, orders.zip_code, orders.quantity
          FROM orders 
          JOIN users ON orders.userId = users.id 
          JOIN products ON orders.productId = products.id
          WHERE DATE(orders.orderDate) = CURDATE() 
          ORDER BY orders.orderDate DESC";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard | Today's Orders</title>
    <link rel="stylesheet" href="css/styles.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet" />
</head>

<body class="sb-nav-fixed">
    <?php include_once('include/navbar-admin.php'); ?>
    <div id="layoutSidenav">
        <?php include_once('include/sidebar-admin.php'); ?>

        <div id="layoutSidenav_content">
            <main class="p-4">
                <div class="container mt-5">

                    <div class="card">
                        <div class="card-header mb-3">
                            <h2 class="my-2" style="color:rgb(76, 0, 255);">📦 Today's Orders</h2>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-success d-none" id="successMsg">Order marked as Delivered!</div>

                            <div class="table-responsive ">
                            <table id="tableadmin" class="table table-striped table-bordered table-hover table-responsive nowrap">                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Product</th>
                                            <th>Customer Name</th>
                                            <th>Contact No</th>
                                            <th>Quantity</th>
                                            <th>Price (₹)</th>
                                            <th>Shipping (₹)</th>
                                            <th>Total (₹)</th>
                                            <th>Shipping Address</th>
                                            <th>Payment Method</th>
                                            <th>Product Image</th>
                                            <th>Status</th>
                                            <th>Order Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = mysqli_fetch_assoc($result)) {
                                            $totalAmount = ($row['productPrice'] * $row['quantity']) + $row['shippingCharge']; ?>
                                            <tr class="text-end" id="order_<?php echo $row['id']; ?>">
                                                <td><?php echo htmlspecialchars($row['id']); ?></td>

                                                <td class="text-start"><?php echo htmlspecialchars($row['productName']); ?></td>
                                                <td class="text-start"><?php echo htmlspecialchars($row['fname']); ?></td>
                                                <td class="text-center"><?php echo htmlspecialchars($row['contactno']); ?></td>
                                                <td class="text-center"><?php echo htmlspecialchars($row['quantity']); ?></td>
                                                <td class="text-end">₹<?php echo number_format($row['productPrice'], 2); ?></td>
                                                <td class="text-end">₹<?php echo number_format($row['shippingCharge'], 2); ?></td>
                                                <td class="text-start"><strong class="text-success">₹<?php echo number_format($totalAmount, 2); ?></strong></td>
                                                <td class="text-start">
                                                    <?php echo htmlspecialchars($row['user_address'] . ", " . $row['city'] . ", " . $row['state'] . " - " . $row['zip_code']); ?>
                                                </td>

                                                <td class="text-center"><?php echo htmlspecialchars($row['paymentMethod']); ?></td>
                                                <td class="text-start">
                                                    <img src="<?php echo htmlspecialchars($row['productImage1']); ?>" class="img-thumbnail" width="80" height="80" alt="Product Image">
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge 
                                            <?php echo ($row['orderStatus'] == 'Delivered') ? 'bg-success' : 'bg-warning'; ?> "
                                                        id="status_<?php echo $row['id']; ?>">
                                                        <?php echo htmlspecialchars($row['orderStatus']); ?>
                                                    </span>
                                                </td>
                                                <td class="text-center"><?php echo date("d-M-Y H:i A", strtotime($row['orderDate'])); ?></td>

                                                <td class="text-start">
                                                    <?php if ($row['orderStatus'] !== 'Delivered') { ?>
                                                        <button class="btn btn-success btn-sm" onclick="markDelivered(<?php echo $row['id']; ?>)">Mark Delivered</button>
                                                    <?php } else { ?>
                                                        <button class="btn btn-secondary btn-sm" disabled>Delivered</button>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        function markDelivered(orderId) {
            if (confirm("Are you sure you want to mark this order as Delivered?")) {
                $.ajax({
                    url: "update_order_status.php",
                    type: "POST",
                    data: {
                        order_id: orderId
                    },
                    success: function(response) {
                        console.log("Server Response:", response);
                        if (response.trim() === "success") {
                            $("#status_" + orderId).text("Delivered").removeClass("bg-warning").addClass("bg-success");
                            $("button[onclick='markDelivered(" + orderId + ")']").replaceWith('<button class="btn btn-secondary btn-sm" disabled>Delivered</button>');
                            $("#successMsg").removeClass("d-none").fadeIn().delay(2000).fadeOut();
                        } else {
                            alert("Error updating order: " + response);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", error);
                        alert("AJAX request failed: " + error);
                    }
                });
            }
        }

        $(document).ready(function() {
            $('#tableadmin').DataTable({
                paging: true,
                searching: true,
                ordering: false,
                info: false,
                lengthChange: false,
                scrollY: "400px",
                scrollX: true,
                scrollCollapse: true,
                autoWidth: false,
                dom: '<"top"f>rt<"bottom"p><"clear">' // Moves search box above the table
            });
        });
    </script>
</body>

</html>