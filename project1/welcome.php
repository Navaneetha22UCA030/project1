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
/* 
// Fetch user orders
$orderQuery = $con->prepare("SELECT orders.*, products.productName 
                             FROM orders 
                             JOIN products ON orders.productId = products.id 
                             WHERE orders.userId = ? 
                             ORDER BY orders.orderDate DESC");
$orderQuery->bind_param("i", $userId);
$orderQuery->execute();
$orderResult = $orderQuery->get_result(); */
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
    <!-- icon-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-pEJc2Yy7OG0/X+FTDV+a7vHb86P6Bj6dqzCEicm2gOvMVkY2wVOoT2NaLgzIn3IjR0MIB4od7QY6Uk5Z3nsNKw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Font Awesome -->
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
                <div class="container-fluid px-4">
                    <h1 class="mt-4">User Dashboard</h1>
                    <hr />

                    <!-- Welcome Message -->
                    <div class="row">
                        <div class="col-xl-5 col-md-6">
                            <div class="card bg-primary text-white mb-4">
                                <div class="card-body">
                                    Welcome To ZamZam Power Tools  🙏🏻 <?php echo htmlspecialchars($user['fname'] . " " . $user['lname']); ?>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="profile.php">View Profile</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
<!-- Orders Table -->
                   <!-- <div class="container mt-5">
                        <div class="card">
                            <div class="card-header">
                                <h2>Your Orders</h2>
                            </div>

                            <?php //if (isset($_SESSION['msg'])) { ?>
                                <div class="alert alert-success">
                                    <?php //echo $_SESSION['msg'];
                                    //unset($_SESSION['msg']); ?>
                                </div>
                            <?php //} ?>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Order ID</th>
                                                <th>Product</th>
                                                <th>Quantity</th>
                                                <th>Payment Method</th>
                                                <th>Status</th>
                                                <th>Order Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                           // $count = 1;
                                            //while ($row = $orderResult->fetch_assoc()) { ?>
                                                <tr>
                                                    <td><?php// echo $count++; ?></td>
                                                    <td><?php// echo htmlspecialchars($row['productName']); ?></td>

                                                    <td><?php// echo $row['quantity']; ?></td>
                                                    <td><?php// echo htmlspecialchars($row['paymentMethod']); ?></td>
                                                    <td>
                                                        <span class="badge bg-<?php// echo ($row['orderStatus'] == 'Delivered') ? 'success' : 'warning'; ?>">
                                                            <?php// echo htmlspecialchars($row['orderStatus']); ?>
                                                        </span>
                                                    </td>
                                                    <td><?php// echo date('d M Y, h:i A', strtotime($row['orderDate'])); ?></td>
                                                    <td>
                                                        <a href="order_details.php?orderId=<?php //echo $row['id']; ?>" class="btn btn-info btn-sm">View</a>
                                                    </td>
                                                </tr>
                                            <?php //} ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div> -->

                </div>
        </div>
        </main>
    </div>
    </div>

    <!-- Footer -->
    <?php include('includes/footer.php'); ?>

    <!-- JavaScript -->
    <script src="js/script.js"></script>
</body>

</html>