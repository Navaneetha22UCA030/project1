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

// Handling POST request for adding a new tool
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tool_type = mysqli_real_escape_string($conn, $_POST['tool_type']);
    $tool_quantity = mysqli_real_escape_string($conn, $_POST['tool_quantity']);
    $tool_rent_rate = mysqli_real_escape_string($conn, $_POST['tool_rent_rate']);
    $tool_brand = mysqli_real_escape_string($conn, $_POST['tool_brand']);

    $tool_image = '';
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

    $sql = "INSERT INTO tool_rental_list (tool_type, tool_image, tool_quantity, tool_rent_rate, tool_brand) 
    VALUES ('$tool_type', '$tool_image', '$tool_quantity', '$tool_rent_rate', '$tool_brand')";

    if (mysqli_query($conn, $sql)) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

// Handling search request
$search = "";
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $sql = "SELECT * FROM tool_rental_list WHERE tool_type LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM tool_rental_list";
}

$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Error fetching data: " . mysqli_error($conn));
}


if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql_delete = "DELETE FROM tool_rental_list WHERE tool_list_id = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param('i', $delete_id);

    if ($stmt_delete->execute()) {
        echo "<script>alert('Record deleted successfully');</script>";
        echo "<script>window.location = 'tool_rental_list_admin.php';</script>";
    } else {
        echo "<script>alert('Error deleting record');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Admin Manage</title>
    <link rel="stylesheet" href="css/styles.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet" />
</head>

<body class="sb-nav-fixed">
    <!-- Include the Navbar -->
    <?php include_once('include/navbar-admin.php'); ?>

    <div id="layoutSidenav">
        <!-- Sidebar -->
        <?php include_once('include/sidebar-admin.php'); ?>

        <!-- Main Content -->
        <div id="layoutSidenav_content">
            <main class="p-4">
                <div class="card">
                    <div class="card-header">
                        <h1 class="h2 text-primary">Tool Rental List</h1>
                    </div>
                    <div class="container mt-4">
                        <!-- Add Power Tool Button -->
                        <div class="row mb-3">
                            <div class="col-12 text-end">
                                <button id="add-tools" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-tool-modal">Add Power Tool</button>
                            </div>
                        </div>
                        <!-- Display Power Tools in Table -->
                        <div class="table-responsive ">
                            <table id="example" class="table p-2 table-bordered table-hover table-secondary">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Tool Type</th>
                                        <th>Tool Image</th>
                                        <th>Tool Quantity</th>
                                        <th>Tool Rent Rate</th>
                                        <th>Tool Brand</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (mysqli_num_rows($result) > 0) {
                                        $counter = 1;
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<tr>
                                            <td>" . $counter++ . "</td>
                                            <td>" . $row['tool_type'] . "</td>
                                            <td><img src='uploads/" . $row['tool_image'] . "' alt='" . $row['tool_type'] . "' class='img-fluid' style='width: 120px; height:70px;'></td>
                                            <td>" . $row['tool_quantity'] . "</td>
                                            <td>" . $row['tool_rent_rate'] . "</td>
                                            <td>" . $row['tool_brand'] . "</td>
                                            <td>
                                                <a href='edit_rental_tool_admin.php?id=" . $row['tool_list_id'] . "' class='btn btn-warning'>Edit</a>
                                        <a href='?delete_id=" . $row['tool_list_id'] . "' class='btn btn-danger btn-sm'>Delete</a>
                                            </td>
                                        </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='8' class='text-center'>No tools found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Add Tool Modal -->
    <div id="add-tool-modal" class="modal" tabindex="-1" aria-labelledby="addToolModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addToolModalLabel">Add Power Tool</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" enctype="multipart/form-data">
                       
                        <div class="mb-3">
                            <label for="tool_type" class="form-label">Power Tool Type</label>
                            <select name="tool_type" id="tool_type" class="form-select" required>
                                <option selected disabled>--Select Tool Type--</option>
                                <option value="circular-saw">Circular Saw</option>
                                <option value="jigsaw">Jigsaw</option>
                                <option value="miter-saw">Miter Saw</option>
                                <option value="table-saw">Table Saw</option>
                                <!-- Add more options as necessary -->
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="tool_image" class="form-label">Tool Image</label>
                            <input type="file" name="tool_image" id="tool_image" class="form-control" accept="image/*" required>
                        </div>
                        <div class="mb-3">
                            <label for="tool_quantity" class="form-label">Tool Quantity</label>
                            <input type="number" name="tool_quantity" id="tool_quantity" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="tool_rent_rate" class="form-label">Tool rent Rate</label>
                            <input type="number" name="tool_rent_rate" id="tool_rent_rate" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="tool_brand" class="form-label">Tool Brand</label>
                            <input type="text" name="tool_brand" id="tool_brand" class="form-control" required>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary mx-2">Submit</button>
                            <button type="button" class="btn btn-secondary mx-2" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
            $('#example').DataTable({
                responsive: true
            });
        });

        $(document).ready(function() {
            $('.power-tools').select2({
                placeholder: "Select a Power Tool",
                allowClear: true
            });
        });
    </script>

</body>

</html>