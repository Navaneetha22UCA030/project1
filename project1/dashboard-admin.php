<?php
session_start();
include_once('includes/config.php');
if (strlen($_SESSION['adminid'] == 0)) {
    header('location:logout.php');
} else {
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Admin | Dashboard</title>
        <link rel="stylesheet" href="style.css">
        <script src="script.js"></script>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    </head>

    <body>
        <!-- Include the Navbar -->
        <?php include_once('include/navbar-admin.php'); ?>

        <!-- Sidebar Overlay -->
        <div class="sidebar-overlay"></div>

        <!-- Sidebar -->
        <?php include_once('include/sidebar-admin.php'); ?>

        <!-- Main Content -->
        <div class="main-content">
            <div class="container-fluid">
                
                <h1 class="mb-4">Dashboard</h1>

                <div class="dashboard-cards">
                    <!-- Total Users Card -->
                    <div class="col-lg-6">
                        <div class="card stats-card bg-primary text-white">
                            <div class="card-body">
                                <h5 class="card-title">Total Registered Users</h5>
                                <h2 class="mb-3">
                                    <?php
                                    $query = mysqli_query($con, "SELECT id FROM users");
                                    echo mysqli_num_rows($query);
                                    ?>
                                </h2>
                                <a href="manage-users-admin.php" class="text-white text-decoration-none">
                                    View Details <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
           
        </script>
    </body>

    </html>
<?php } ?>