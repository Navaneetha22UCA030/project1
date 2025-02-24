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
        <title>Admin Dashboard | Bootstrap 5</title>
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
    <style>
        .offcanvas {
            width: 60% !important; /* Adjust sidebar width for better responsiveness */
        }
        @media (min-width: 768px) {
            .offcanvas {
                width: 300px !important; /* Standard width for larger screens */
            }
        }
    </style>
    <body class="sb-nav-fixed">
        <?php include_once('demonav.php'); ?>
            <!-- Sidebar -->
            <?php include_once('demosidebar.php'); ?>
                <main class="p-4">
                    <h1 class="m-2">Dashboard</h1>
                    <div class="row">
                        <?php
                        $query = mysqli_query($con, "SELECT id FROM users");
                        $totalusers = mysqli_num_rows($query);
                        ?>
                        <!-- Total Registered Users Card -->
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    Total Registered Users: <span style="font-size:22px;"><?php echo $totalusers; ?></span>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white" href="manage-users-admin.php">View Details</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>


    </body>

    </html>

<?php } ?>