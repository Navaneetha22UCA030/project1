<?php
session_start();
include_once('includes/config.php');

if (strlen($_SESSION['adminid']) == 0) {
    header('location:logout.php');
    exit();
}

$productID = intval($_GET['id']);

// Fetch product details
$query = mysqli_query($con, "SELECT * FROM products WHERE id='$productID'");
$product = mysqli_fetch_assoc($query);

if (isset($_POST['update'])) {
    $category = mysqli_real_escape_string($con, $_POST['category']);
    $productName = mysqli_real_escape_string($con, $_POST['productName']);
    $productCompany = mysqli_real_escape_string($con, $_POST['productCompany']);
    $productPriceBD = mysqli_real_escape_string($con, $_POST['productPriceBeforeDiscount']);
    $productPrice = mysqli_real_escape_string($con, $_POST['productPrice']);
    $productDescription = mysqli_real_escape_string($con, $_POST['productDescription']);
    $shippingCharge = mysqli_real_escape_string($con, $_POST['shippingCharge']);
    $productAvailability = mysqli_real_escape_string($con, $_POST['productAvailability']);

    // Handle Image Uploads
    $targetDir = "uploads/";
    $image1 = !empty($_FILES["productImage1"]["name"]) ? $targetDir . basename($_FILES["productImage1"]["name"]) : $product['productImage1'];
    $image2 = !empty($_FILES["productImage2"]["name"]) ? $targetDir . basename($_FILES["productImage2"]["name"]) : $product['productImage2'];
    $image3 = !empty($_FILES["productImage3"]["name"]) ? $targetDir . basename($_FILES["productImage3"]["name"]) : $product['productImage3'];

    if (!empty($_FILES["productImage1"]["name"])) move_uploaded_file($_FILES["productImage1"]["tmp_name"], $image1);
    if (!empty($_FILES["productImage2"]["name"])) move_uploaded_file($_FILES["productImage2"]["tmp_name"], $image2);
    if (!empty($_FILES["productImage3"]["name"])) move_uploaded_file($_FILES["productImage3"]["tmp_name"], $image3);

    // Update Query
    $sql = "UPDATE products SET category=?, productName=?, productCompany=?, productPriceBeforeDiscount=?, productPrice=?, productDescription=?, productImage1=?, productImage2=?, productImage3=?, shippingCharge=?, productAvailability=? WHERE id=?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sssssssssssi", $category, $productName, $productCompany, $productPriceBD, $productPrice, $productDescription, $image1, $image2, $image3, $shippingCharge, $productAvailability, $productID);
    $stmt->execute();

    $_SESSION['msg'] = "Product Updated Successfully!";
    header("Location: insert_poroduct_admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Product</title>
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Edit Product</h2>

        <?php if (isset($_SESSION['msg'])) { ?>
            <div class="alert alert-success"><?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
        <?php } ?>

        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label>Category</label>
                <select name="category" class="form-control" required>
                    <option value="">Select Category</option>
                    <?php
                    $query = mysqli_query($con, "SELECT * FROM category");
                    while ($row = mysqli_fetch_assoc($query)) { ?>
                        <option value="<?php echo $row['id']; ?>" <?php echo ($row['id'] == $product['category']) ? "selected" : ""; ?>>
                            <?php echo $row['categoryName']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="mb-3">
                <label>Product Name</label>
                <input type="text" name="productName" class="form-control" value="<?php echo $product['productName']; ?>" required>
            </div>

            <div class="mb-3">
                <label>Product Company</label>
                <input type="text" name="productCompany" class="form-control" value="<?php echo $product['productCompany']; ?>" required>
            </div>

            <div class="mb-3">
                <label>Price Before Discount</label>
                <input type="text" name="productPriceBeforeDiscount" class="form-control" value="<?php echo $product['productPriceBeforeDiscount']; ?>" required>
            </div>

            <div class="mb-3">
                <label>Selling Price</label>
                <input type="text" name="productPrice" class="form-control" value="<?php echo $product['productPrice']; ?>" required>
            </div>

            <div class="mb-3">
                <label>Product Description</label>
                <textarea name="productDescription" class="form-control" rows="4" required><?php echo $product['productDescription']; ?></textarea>
            </div>

            <div class="mb-3">
                <label>Shipping Charge</label>
                <input type="text" name="shippingCharge" class="form-control" value="<?php echo $product['shippingCharge']; ?>" required>
            </div>

            <div class="mb-3">
                <label>Availability</label>
                <select name="productAvailability" class="form-control" required>
                    <option value="In Stock" <?php if ($product['productAvailability'] == "In Stock") echo "selected"; ?>>In Stock</option>
                    <option value="Out of Stock" <?php if ($product['productAvailability'] == "Out of Stock") echo "selected"; ?>>Out of Stock</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Product Images</label><br>
                <img src="<?php echo $product['productImage1']; ?>" width="50">
                <input type="file" name="productImage1" class="form-control">
                
                <img src="<?php echo $product['productImage2']; ?>" width="50">
                <input type="file" name="productImage2" class="form-control">
                
                <img src="<?php echo $product['productImage3']; ?>" width="50">
                <input type="file" name="productImage3" class="form-control">
            </div>

            <button type="submit" name="update" class="btn btn-primary">Update Product</button>
            <a href="insert_product_admin.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
