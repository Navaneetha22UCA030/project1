<?php
session_start();
include_once('includes/config.php');

if (strlen($_SESSION['adminid']) == 0) {
    header('location:logout.php');
    exit;
}

// Fetch delivered orders
$query = "SELECT orders.*, users.fname, users.contactno, products.productName 
          FROM orders 
          JOIN users ON orders.userId = users.id 
          JOIN products ON orders.productId = products.id
          WHERE orders.orderStatus = 'Delivered' 
          ORDER BY orders.orderDate DESC";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard | Delivered Orders</title>
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
    <style>
        body {
            background-color: #f8f9fa;
        }

        .table thead {
            background: #343a40;
            color: white;
        }

        .table tbody tr:hover {
            background: #f1f1f1;
        }
    </style>
</head>

<body class="sb-nav-fixed">
    <!-- Include the Navbar -->
    <?php include_once('include/navbar-admin.php'); ?>

    <div id="layoutSidenav">
        <!-- Sidebar -->
        <?php include_once('include/sidebar-admin.php'); ?>

        <div id="layoutSidenav_content">
            <main class="p-4">
                <div class="container mt-5">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="mb-3">✅ Delivered Orders</h2>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="tableadmin">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Customer Name</th>
                                            <th>Contact No</th>
                                            <th>Product</th>
                                            <th>Payment Method</th>
                                            <th>Status</th>
                                            <th>Order Date</th>
                                            <th>Shipping Address</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                            <tr>
                                                <td><?php echo $row['id']; ?></td>
                                                <td><?php echo htmlspecialchars($row['fname']); ?></td>
                                                <td><?php echo htmlspecialchars($row['contactno']); ?></td>
                                                <td><?php echo htmlspecialchars($row['productName']); ?></td>
                                                <td><?php echo htmlspecialchars($row['paymentMethod']); ?></td>
                                                <td><span class="badge bg-success"><?php echo htmlspecialchars($row['orderStatus']); ?></span></td>
                                                <td><?php echo date("d-M-Y H:i A", strtotime($row['orderDate'])); ?></td>
                                                <td>
                                                    <?php echo htmlspecialchars($row['user_address'] . ", " . $row['city'] . ", " . $row['state'] . " - " . $row['zip_code']); ?>
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
<?php
?>