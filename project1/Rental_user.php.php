<?php
session_start();
include_once('includes/config.php');

// Check if user is logged in
if (strlen($_SESSION['id'] == 0)) {
    header('location:logout.php');
    exit;
} else {
?>
<?php

// Fetch rental tools data
$query = mysqli_query($con, "SELECT * FROM  tool_rental_list");

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Rental Products | Online Tool Rental</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <!-- Custom Styles -->
    <link href="css/styles.css" rel="stylesheet" />

    <style>
        body {
            background-color: #f8f9fa;
        }

        .tool-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            overflow: hidden;
        }

        .tool-card:hover {
            transform: scale(1.02);
            box-shadow: 0px 10px 15px rgba(0, 0, 0, 0.1);
        }

        .card img {
            height: 200px;
            object-fit: cover;
        }
    </style>
</head>

<body class="sb-nav-fixed">
    <!-- Include Navbar -->
    <?php include_once('includes/navbar.php'); ?>

    <div id="layoutSidenav">
        <!-- Include Sidebar -->
        <?php include_once('includes/sidebar.php'); ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container mt-4">
                    <h2 class="text-center text-primary">Available Rental Tools</h2>
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mt-3">
                        <?php
                        if (mysqli_num_rows($query) > 0) {
                            while ($tool = mysqli_fetch_assoc($query)) { ?>
                                <div class="col">
                                    <div class="card tool-card h-100 shadow-sm">
                                        <img src="uploads/<?php echo $tool['tool_image']; ?>" class="card-img-top" alt="<?php echo $tool['tool_type']; ?>">
                                        <div class="card-body">
                                            <h5 class="card-title text-primary"><?php echo $tool['tool_type']; ?></h5>
                                            <p class="text-muted mb-1"><strong>Brand:</strong> <?php echo $tool['tool_brand']; ?></p>
                                            <p class="text-muted mb-1"><strong>Available:</strong> <?php echo $tool['tool_quantity']; ?> in stock</p>
                                            <p class="text-success"><strong>Rent Rate:</strong> $<?php echo $tool['tool_rent_rate']; ?> per day</p>
                                            <a href="rent_tool.php?id=<?php echo $tool['id']; ?>" class="btn btn-primary w-100">
                                                <i class="bi bi-cart-fill"></i> Rent Now
                                            </a>
                                        </div>
                                    </div>
                                </div>
                        <?php }
                        } else { ?>
                            <div class="col-12 text-center">
                                <p class="text-muted">No rental tools available.</p>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>
<?php
}
?>