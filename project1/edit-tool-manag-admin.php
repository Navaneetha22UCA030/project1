<?php
session_start();
include_once('includes/config.php');

if (strlen($_SESSION['adminid'] == 0)) {
    header('location:logout.php');
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project1";
$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the tool ID from the URL
if (isset($_GET['id'])) {
    $tool_id = $_GET['id'];

    // Fetch tool details from the database
    $sql = "SELECT * FROM power_tools WHERE id = '$tool_id'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $tool = mysqli_fetch_assoc($result);
    } else {
        echo "Tool not found!";
        exit;
    }
} else {
    echo "Invalid tool ID!";
    exit;
}

// Handle form submission for editing the tool
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tool_name = mysqli_real_escape_string($conn, $_POST['tool_name']);
    $tool_type = mysqli_real_escape_string($conn, $_POST['tool_type']);
    $tool_quantity = mysqli_real_escape_string($conn, $_POST['tool_quantity']);
    $tool_sales_rate = mysqli_real_escape_string($conn, $_POST['tool_sales_rate']);
    $tool_sales_brand = mysqli_real_escape_string($conn, $_POST['tool_sales_brand']);

    $tool_image = $tool['tool_image'];  // Keep the old image by default
    if (isset($_FILES['tool_image']) && $_FILES['tool_image']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $target_file = $target_dir . basename($_FILES["tool_image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_extensions = array("jpg", "jpeg", "png", "gif");
        if (in_array($imageFileType, $allowed_extensions)) {
            if (move_uploaded_file($_FILES["tool_image"]["tmp_name"], $target_file)) {
                $tool_image = basename($_FILES["tool_image"]["name"]);
            } else {
                echo "<script>alert('Sorry, there was an error uploading your file.')</script>";
            }
        } else {
            echo "<script>alert('Only JPG, JPEG, PNG & GIF files are allowed.')</script>";
        }
    }

    // Update tool data in the database
    $sql_update = "UPDATE power_tools 
                   SET tool_name = '$tool_name', tool_type = '$tool_type', tool_image = '$tool_image', 
                       tool_quantity = '$tool_quantity', tool_sales_rate = '$tool_sales_rate', tool_sales_brand = '$tool_sales_brand'
                   WHERE id = '$tool_id'";

    if (mysqli_query($conn, $sql_update)) {
        header("Location:tools-manage-admin.php");  // Redirect to manage tools page
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Edit Tool</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <!-- Include the Navbar -->
    <?php include_once('include/navbar-admin.php'); ?>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h2>Edit Power Tool</h2>
                <a href="tools-manage-admin.php" class="btn btn-secondary d-flex justify-content-end">Back</a> 
                    
                </div>
            </div>
            <div class="card-body">


                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="mb-3 p-1">
                        <label for="tool_name" class="form-label">Tool Name</label>
                        <input type="text" name="tool_name" id="tool_name" class="form-control" value="<?php echo $tool['tool_name']; ?>" required>
                    </div>

                    <div class="mb-3 p-1">
                        <label for="tool_type" class="form-label">Tool Type</label>
                        <select name="tool_type" id="tool_type" class="form-select" required>
                            <option value="circular-saw" <?php echo ($tool['tool_type'] == 'circular-saw') ? 'selected' : ''; ?>>Circular Saw</option>
                            <option value="jigsaw" <?php echo ($tool['tool_type'] == 'jigsaw') ? 'selected' : ''; ?>>Jigsaw</option>
                            <option value="miter-saw" <?php echo ($tool['tool_type'] == 'miter-saw') ? 'selected' : ''; ?>>Miter Saw</option>
                            <option value="table-saw" <?php echo ($tool['tool_type'] == 'table-saw') ? 'selected' : ''; ?>>Table Saw</option>
                        </select>
                    </div>

                    <div class="mb-3 p-1">
                        <label for="tool_image" class="form-label">Tool Image</label>
                        <input type="file" name="tool_image" id="tool_image" class="form-control" accept="image/*">
                        <small class="form-text text-muted">Current Image: <img src="uploads/<?php echo $tool['tool_image']; ?>" alt="Tool Image" width="100"></small>
                    </div>

                    <div class="mb-3 p-1">
                        <label for="tool_quantity" class="form-label">Tool Quantity</label>
                        <input type="number" name="tool_quantity" id="tool_quantity" class="form-control" value="<?php echo $tool['tool_quantity']; ?>" required>
                    </div>

                    <div class="mb-3 p-1">
                        <label for="tool_sales_rate" class="form-label">Tool Sales Rate</label>
                        <input type="number" name="tool_sales_rate" id="tool_sales_rate" class="form-control" value="<?php echo $tool['tool_sales_rate']; ?>" required>
                    </div>

                    <div class="mb-3 p-1">
                        <label for="tool_sales_brand" class="form-label">Tool Brand</label>
                        <input type="text" name="tool_sales_brand" id="tool_sales_brand" class="form-control" value="<?php echo $tool['tool_sales_brand']; ?>" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Tool</button>
                    <a href="tools-manage-admin.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>