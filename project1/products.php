<?php
session_start();
include_once('includes/config.php');

// Check if user is logged in
if (strlen($_SESSION['id']) == 0) {
    header('location:logout.php');
    exit;
}

$categoryQuery = mysqli_query($con, "SELECT * FROM category");

// Fetch products based on category and search filter
$where = " WHERE 1=1";
if (isset($_GET['category']) && !empty($_GET['category'])) {
    $categoryId = intval($_GET['category']);
    $where .= " AND category='$categoryId'";
}
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = mysqli_real_escape_string($con, $_GET['search']);
    $where .= " AND productName LIKE '%$searchTerm%'";
}
$productQuery = mysqli_query($con, "SELECT * FROM products" . $where);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Shop | Power Tools Product</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <!-- Custom Styles -->
    <link href="css/styles.css" rel="stylesheet" />
</head>
<style>
        body {
            background-color: rgb(206, 230, 255);
        }

        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            overflow: hidden;
        }

        #card-product:hover {
            transform: scale(1.02);
            box-shadow: 1px 3px 0px 0px rgba(0, 0, 0, 0.1);
        }

        #card-product {
            border: rgb(255, 0, 179) 2px solid;
        }
    </style>

<body class="sb-nav-fixed">
    <?php include_once('includes/navbar.php'); ?>
    <div id="layoutSidenav">
        <?php include_once('includes/sidebar.php'); ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container mt-4">
                    <div class="card p-3 bg-light">
                        <div class="card-header text-start">
                            <h2 style="color: rgb(255, 94, 0);">Zam Zam Power Tools</h2>
                            <hr>
                            <form method="GET" class="row g-2" onsubmit="clearSearch()">
                                <div class="col-md-6">
                                    <select name="category" class="form-control form-select" onchange="this.form.submit()">
                                        <option value="">All Categories</option>
                                        <?php while ($category = mysqli_fetch_assoc($categoryQuery)) { ?>
                                            <option value="<?php echo $category['id']; ?>" <?php echo isset($_GET['category']) && $_GET['category'] == $category['id'] ? 'selected' : ''; ?>>
                                                <?php echo $category['categoryName']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" id="searchBox" name="search" class="form-control" placeholder="Search product..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>" />
                                </div>
                            </form>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <?php if (mysqli_num_rows($productQuery) > 0) {
                                    while ($product = mysqli_fetch_assoc($productQuery)) { ?>
                                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                            <div class="card h-100" id="card-product">
                                                <div id="carousel<?php echo $product['id']; ?>" class="carousel slide" data-bs-ride="carousel">
                                                    <div class="carousel-inner">
                                                        <div class="carousel-item active">
                                                            <img src="<?php echo $product['productImage1']; ?>" class="d-block w-100" alt="Product Image 1">
                                                        </div>
                                                        <?php if (!empty($product['productImage2'])) { ?>
                                                            <div class="carousel-item">
                                                                <img src="<?php echo $product['productImage2']; ?>" class="d-block w-100" alt="Product Image 2">
                                                            </div>
                                                        <?php } ?>
                                                        <?php if (!empty($product['productImage3'])) { ?>
                                                            <div class="carousel-item">
                                                                <img src="<?php echo $product['productImage3']; ?>" class="d-block w-100" alt="Product Image 3">
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="card-body" style="background-color:rgb(85, 255, 218)">
                                                    <p class="card-title text-primary">Power Tool: <?php echo $product['productName']; ?></p>
                                                    <p class="card-text text-muted"><?php echo substr($product['productDescription'], 0, 80) . '...'; ?></p>
                                                    <p style="color:rgb(255, 115, 0);">Brand: <?php echo $product['productCompany']; ?></p>
                                                    <div class="d-flex justify-content-between">
                                                        <del class="text-danger fw-bold">$<?php echo $product['productPriceBeforeDiscount']; ?></del>
                                                        <p class="text-success fw-bold">$<?php echo $product['productPrice']; ?></p>
                                                    </div>
                                                    <a href="product_details.php?id=<?php echo $product['id']; ?>" class="btn btn-primary w-100">View Details</a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php }
                                } else { ?>
                                    <div class="col-12 text-center">
                                        <p class="text-muted">No products found.</p>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        function clearSearch() {
            setTimeout(() => {
                document.getElementById("searchBox").value = "";
            }, 500);
        }
    </script>
</body>
</html>
