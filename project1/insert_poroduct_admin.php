<?php
session_start();
include_once('includes/config.php');

if (strlen($_SESSION['adminid']) == 0) {
    header('location:logout.php');
    exit();
}

// Insert Product
if (isset($_POST['submit'])) {
    $category = mysqli_real_escape_string($con, $_POST['category']);
    $productName = mysqli_real_escape_string($con, $_POST['productName']);
    $productCompany = mysqli_real_escape_string($con, $_POST['productCompany']);
    $productPriceBD = mysqli_real_escape_string($con, $_POST['productPriceBeforeDiscount']);
    $productPrice = mysqli_real_escape_string($con, $_POST['productPrice']);
    $productDescription = mysqli_real_escape_string($con, $_POST['productDescription']);
    $shippingCharge = mysqli_real_escape_string($con, $_POST['shippingCharge']);
    $productAvailability = mysqli_real_escape_string($con, $_POST['productAvailability']);
    $paymentMethod = mysqli_real_escape_string($con, $_POST['paymentMethod']);

    // Handle Image Uploads
    $targetDir = "uploads/";
    function uploadImage($file, $targetDir)
    {
        if (!empty($file['name'])) {
            $fileName = uniqid() . "_" . basename($file["name"]);
            $targetFile = $targetDir . $fileName;
            move_uploaded_file($file["tmp_name"], $targetFile);
            return $targetFile;
        }
        return null;
    }

    $image1 = uploadImage($_FILES["productImage1"], $targetDir);
    $image2 = uploadImage($_FILES["productImage2"], $targetDir);
    $image3 = uploadImage($_FILES["productImage3"], $targetDir);

    // Insert Query
    $sql = "INSERT INTO products (category, productName, productCompany, productPriceBeforeDiscount, productPrice, productDescription, productImage1, productImage2, productImage3, shippingCharge, productAvailability, paymentMethod, postingDate)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssssssssssss", $category, $productName, $productCompany, $productPriceBD, $productPrice, $productDescription, $image1, $image2, $image3, $shippingCharge, $productAvailability, $paymentMethod);
    $stmt->execute();

    $_SESSION['msg'] = "Product Inserted Successfully!";
}

$result = mysqli_query($con, "SELECT products.*, category.categoryName FROM products 
                              JOIN category ON products.category = category.id");
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Admin Dashboard | Insert Product</title>
        <link rel="stylesheet" href="css/styles.css">
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font Awesome -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
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
                    <div class="card">
                        <div class="card-header">
                            <h2 class="mb-4">Insert Product</h2>
                        </div>

                        <div class="card-body">
                            <?php if (isset($_SESSION['msg'])) { ?>
                                <div class="alert alert-success"> <?php echo $_SESSION['msg'];
                                                                    unset($_SESSION['msg']); ?> </div>
                            <?php } ?>

                            <form method="post" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label class="form-label">Category</label>
                                    <select name="category" class="form-control" required>
                                        <option value="">Select Category</option>
                                        <?php
                                        $query = mysqli_query($con, "SELECT * FROM category");
                                        while ($row = mysqli_fetch_assoc($query)) { ?>
                                            <option value="<?php echo $row['id']; ?>"> <?php echo $row['categoryName']; ?> </option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Product Name</label>
                                    <input type="text" name="productName" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Product Company</label>
                                    <input type="text" name="productCompany" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Price Before Discount</label>
                                    <input type="text" name="productPriceBeforeDiscount" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Selling Price</label>
                                    <input type="text" name="productPrice" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Product Description</label>
                                    <textarea name="productDescription" class="form-control" rows="4" required></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Shipping Charge</label>
                                    <input type="text" name="shippingCharge" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Availability</label>
                                    <select name="productAvailability" class="form-control" required>
                                        <option value="In Stock">In Stock</option>
                                        <option value="Out of Stock">Out of Stock</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Payment Method</label>
                                    <select name="paymentMethod" class="form-control" required>
                                        <option value="Online">Online Payment</option>
                                        <option value="COD">Cash on Delivery</option>
                                        <option value="Both">Both</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Product Images</label>
                                    <input type="file" name="productImage1" class="form-control my-1" required>
                                    <input type="file" name="productImage2" class="form-control my-1">
                                    <input type="file" name="productImage3" class="form-control my-1">
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                 <button type="submit" name="submit" class="btn btn-primary w-75 d-flex align-items-center justify-content-center">Insert Product</button>
                                </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <hr>
                    <div class="card">
                        <div class="card-header">
                            <h2 class="mt-4">Product List</h2>
                        </div>
                        <div class="card-body">
                            <table class="table display nowrap table-borderd table-striped table-bordered table-bordered table-hover table-secondary" id="tableadmin">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Category</th>
                                        <th>Name</th>
                                        <th>Company</th>
                                        <th>Price (BD)</th>
                                        <th>Selling Price</th>
                                        <th>Availability</th>
                                        <th>Payment</th>
                                        <th>Image</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                        <tr>
                                            <td><?php echo $row['id']; ?></td>
                                            <td><?php echo $row['categoryName']; ?></td>
                                            <td><?php echo $row['productName']; ?></td>
                                            <td><?php echo $row['productCompany']; ?></td>
                                            <td><?php echo $row['productPriceBeforeDiscount']; ?></td>
                                            <td><?php echo $row['productPrice']; ?></td>
                                            <td><?php echo $row['productAvailability']; ?></td>
                                            <td><?php echo $row['paymentMethod']; ?></td>
                                            <td><img src="<?php echo $row['productImage1']; ?>" width="50"></td>
                                            <td>
                                                <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                                <a href="delete_product.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
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