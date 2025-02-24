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
    $tool_name = mysqli_real_escape_string($conn, $_POST['tool_name']);
    $tool_type = mysqli_real_escape_string($conn, $_POST['tool_type']);
    $tool_quantity = mysqli_real_escape_string($conn, $_POST['tool_quantity']);
    $tool_sales_rate = mysqli_real_escape_string($conn, $_POST['tool_sales_rate']);
    $tool_sales_brand = mysqli_real_escape_string($conn, $_POST['tool_sales_brand']);

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

    $sql = "INSERT INTO power_tools (tool_name, tool_type, tool_image, tool_quantity, tool_sales_rate, tool_sales_brand) 
            VALUES ('$tool_name', '$tool_type', '$tool_image', '$tool_quantity', '$tool_sales_rate', '$tool_sales_brand')";

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
    $sql = "SELECT * FROM power_tools WHERE tool_name LIKE '%$search%' OR tool_type LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM power_tools";
}

$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Error fetching data: " . mysqli_error($conn));
}


if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql_delete = "DELETE FROM power_tools WHERE id = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param('i', $delete_id);

    if ($stmt_delete->execute()) {
        echo "<script>alert('Record deleted successfully');</script>";
        echo "<script>window.location = 'tools-manage-admin.php';</script>";
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
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
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
    <!-- Include the Navbar -->
    <?php include_once('include/navbar-admin.php'); ?>

    <!-- Sidebar -->
    <?php include_once('include/sidebar-admin.php'); ?>
    <div class="row">
        <div class="col-lg-12">
        <div class="container-fluied my-3 mx-2">
            <div class="card p-2">
                <div class="card-header">
                    <h1 class="h2 text-primary">Power Tools Manage</h1>
                </div>
                <div class="container-fluied mt-4">
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
                                    <th>Tool Name</th>
                                    <th>Tool Type</th>
                                    <th>Tool Image</th>
                                    <th>Tool Quantity</th>
                                    <th>Tool Sales Rate</th>
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
                                            <td>" . $row['tool_name'] . "</td>
                                            <td>" . $row['tool_type'] . "</td>
                                            <td><img src='uploads/" . $row['tool_image'] . "' alt='" . $row['tool_name'] . "' class='img-fluid' style='width: 120px; height:70px;'></td>
                                            <td>" . $row['tool_quantity'] . "</td>
                                            <td>" . $row['tool_sales_rate'] . "</td>
                                            <td>" . $row['tool_sales_brand'] . "</td>
                                            <td>
                                                <a href='edit-tool-manag-admin.php?id=" . $row['id'] . "' class='btn btn-warning'>Edit</a>
                                        <a href='?delete_id=" . $row['id'] . "' class='btn btn-danger btn-sm'>Delete</a>
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
            </div>
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
                            <label for="tool_name" class="form-label">Power Tool Name</label>
                            <input type="text" name="tool_name" id="tool_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="tool_type" class="form-label">Power Tool Type</label>
                            <select name="tool_type" id="tool_type" class="form-select power-tools" search required>
                                <option selected disabled>--Select Tool Type--</option>
                                <option value="drill">Drill</option>
                                <option value="impact-driver">Impact Driver</option>
                                <option value="cordless-screwdriver">Cordless Screwdriver</option>
                                <option value="angle-grinder">Angle Grinder</option>
                                <option value="rotary-tool">Rotary Tool (Dremel)</option>
                                <option value="oscillating-tool">Oscillating Multi-Tool</option>
                                <option value="heat-gun">Heat Gun</option>
                                <option value="jigsaw">Jigsaw</option>
                                <option value="reciprocating-saw">Reciprocating Saw</option>
                                <option value="circular-saw">Circular Saw</option>
                                <option value="belt-sander">Belt Sander</option>
                                <option value="random-orbital-sander">Random Orbital Sander</option>
                                <option value="detail-sander">Detail Sander</option>
                                <option value="router">Router</option>
                                <option value="planer">Planer</option>
                                <option value="table-saw">Table Saw</option>
                                <option value="miter-saw">Miter Saw</option>
                                <option value="scroll-saw">Scroll Saw</option>
                                <option value="band-saw">Band Saw</option>
                                <option value="tile-saw">Tile Saw</option>
                                <option value="lathe">Lathe</option>
                                <option value="wood-chipper">Wood Chipper</option>
                                <option value="log-splitter">Log Splitter</option>
                                <option value="bench-grinder">Bench Grinder</option>
                                <option value="air-compressor">Air Compressor</option>
                                <option value="nail-gun">Nail Gun (Brad Nailer, Framing Nailer)</option>
                                <option value="concrete-mixer">Concrete Mixer</option>
                                <option value="demolition-hammer">Demolition Hammer</option>
                                <option value="jackhammer">Jackhammer</option>
                                <option value="pressure-washer">Pressure Washer</option>
                                <option value="paint-sprayer">Paint Sprayer</option>
                                <option value="dust-extractor">Dust Extractor</option>
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
                            <label for="tool_sales_rate" class="form-label">Tool Sales Rate</label>
                            <input type="number" name="tool_sales_rate" id="tool_sales_rate" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="tool_sales_brand" class="form-label">Tool Brand</label>
                            <input type="text" name="tool_sales_brand" id="tool_sales_brand" class="form-control" required>
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