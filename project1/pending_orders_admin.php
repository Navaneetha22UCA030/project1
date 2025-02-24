<?php
session_start();
include_once('includes/config.php');

// Secure session check
if (!isset($_SESSION['adminid']) || strlen($_SESSION['adminid']) == 0) {
    header('location:logout.php');
    exit;
}

// Fetch pending orders with product details
$query = "SELECT orders.*, users.fname, users.contactno, 
                 products.productName, products.productImage1, 
                 products.productPrice, products.shippingCharge, orders.quantity
          FROM orders 
          JOIN users ON orders.userId = users.id 
          JOIN products ON orders.productId = products.id
          WHERE orders.orderStatus = 'Pending' 
          ORDER BY orders.orderDate DESC";

$result = mysqli_query($con, $query) or die("Query failed: " . mysqli_error($con));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard | Pending Orders</title>
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
                            <h2>📦 Pending Orders</h2>
                        </div>
                        <div class="alert alert-success d-none" id="successMsg">Order status updated successfully!</div>
                        <div class="table-responsive">
                            <table class="table display nowrap table-borderd table-striped table-bordered table-bordered table-hover table-secondary" id="tableadmin">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>

                                        <th>Product</th>
                                        <th>Customer Name</th>
                                        <th>Contact No</th>
                                        <th>Shipping Address</th>
                                        <th>Quantity</th>
                                        <th>Price (₹)</th>
                                        <th>Shipping (₹)</th>
                                        <th>Total (₹)</th>
                                        <th>Product Image</th>
                                        <th>Payment Method</th>
                                        <th>Status</th>
                                        <th>Order Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = mysqli_fetch_assoc($result)) {
                                        $totalAmount = ($row['productPrice'] * $row['quantity']) + $row['shippingCharge'];
                                    ?>
                                        <tr id="order_<?php echo $row['id']; ?>">
                                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                                            <td><?php echo htmlspecialchars($row['productName']); ?></td>
                                            <td><?php echo htmlspecialchars($row['fname']); ?></td>
                                            <td><?php echo htmlspecialchars($row['contactno']); ?></td>
                                            <td class="text-start">
                                                <?php echo htmlspecialchars($row['user_address'] . ", " . $row['city'] . ", " . $row['state'] . " - " . $row['zip_code']); ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                                            <td>₹<?php echo number_format($row['productPrice'], 2); ?></td>
                                            <td>₹<?php echo number_format($row['shippingCharge'], 2); ?></td>
                                            <td><strong class="text-success">₹<?php echo number_format($totalAmount, 2); ?></strong></td>
                                            <td><img src="<?php echo htmlspecialchars($row['productImage1']); ?>" class="img-thumbnail" width="80" height="80"></td>
                                            <td><?php echo htmlspecialchars($row['paymentMethod']); ?></td>
                                            <td><span class="badge bg-warning" id="status_<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['orderStatus']); ?></span></td>
                                            <td><?php echo date("d-M-Y H:i A", strtotime($row['orderDate'])); ?></td>
                                            <td>
                                                <button class="btn btn-success btn-sm mark-delivered" data-orderid="<?php echo $row['id']; ?>">Mark as Delivered</button>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".mark-delivered").forEach(button => {
            button.addEventListener("click", function() {
                let orderId = this.getAttribute("data-orderid");
                if (confirm("Are you sure you want to mark this order as Delivered?")) {
                    fetch("admin_update_order.php", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/x-www-form-urlencoded"
                            },
                            body: "orderId=" + orderId + "&status=Delivered"
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === "success") {
                                document.getElementById("status_" + orderId).textContent = "Delivered";
                                document.getElementById("status_" + orderId).classList.replace("bg-warning", "bg-success");
                                let button = document.querySelector("[data-orderid='" + orderId + "']");
                                button.classList.replace("btn-success", "btn-secondary");
                                button.textContent = "Delivered";
                                button.setAttribute("disabled", true);
                                document.getElementById("successMsg").classList.remove("d-none");
                                setTimeout(() => {
                                    document.getElementById("successMsg").classList.add("d-none");
                                }, 3000);
                            } else {
                                alert(data.message);
                            }
                        })
                        .catch(error => console.error("Error:", error));
                }
            });
        });
    });

    <script>
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
</html>