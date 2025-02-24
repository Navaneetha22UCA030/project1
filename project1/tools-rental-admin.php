<?php
session_start();
include_once('includes/config.php');

if (strlen($_SESSION['adminid'] == 0)) {
    header('location:logout.php');
    exit;
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project1";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $buyer_name = $_POST['username'];
    $tool_type = $_POST['toolType'];
    $phone_no = $_POST['phone_no'];
    $check_out_date = $_POST['check_out_date'];
    $given_by = $_POST['given_by'];
    $check_in_date = NULL; // Default to NULL (not checked in yet)

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = $_FILES['image']['name'];
        $image_temp = $_FILES['image']['tmp_name'];
        $target_directory = "uploads/";
        $target_file = $target_directory . basename($image);

        if (move_uploaded_file($image_temp, $target_file)) {
            $sql = "INSERT INTO rental_tools (username, tool_type, phone_no, check_out_date, check_in_date, given_by, image) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssss", $buyer_name, $tool_type, $phone_no, $check_out_date, $check_in_date, $given_by, $image);

            if ($stmt->execute()) {
                echo "<script>alert('Data and image successfully uploaded!');</script>";
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
            } else {
                echo "Error: " . $stmt->error;
            }
        } else {
            echo "Error uploading image.";
        }
    } else {
        echo "No image uploaded or there was an error with the upload.";
    }
}

// Handle record deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql_delete = "DELETE FROM rental_tools WHERE id = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param('i', $delete_id);

    if ($stmt_delete->execute()) {
        echo "<script>alert('Record deleted successfully');</script>";
        echo "<script>window.location = 'tools-rental-admin.php';</script>";
    } else {
        echo "<script>alert('Error deleting record');</script>";
    }
}

// Handle check-in (Update the current date and time for check-in)
if (isset($_GET['checkin_id'])) {
    $checkin_id = $_GET['checkin_id'];
    $check_in_date = date('Y-m-d H:i:s');  // Get the current date and time

    $sql_checkin = "UPDATE rental_tools SET check_in_date = ? WHERE id = ?";
    $stmt_checkin = $conn->prepare($sql_checkin);
    $stmt_checkin->bind_param('si', $check_in_date, $checkin_id);

    if ($stmt_checkin->execute()) {
        echo "<script>alert('Check-in successful');</script>";
        echo "<script>window.location = 'tools-rental-admin.php';</script>";
    } else {
        echo "<script>alert('Error updating check-in date');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Admin Manage</title>
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
                <div class="container mt-5">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="h2 text-primary">Power Tools Rental Manage</h2>
                        </div>
                        <div class="card-body">
                            <form method="POST" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-lg-4 p-2">
                                        <label for="username">Buyer name</label>
                                        <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
                                    </div>
                                    <div class="col-lg-4 p-2">
                                        <label for="toolType">Tool Type</label>
                                        <select class="form-control power-tools" id="toolType" name="toolType" required>
                                            <option selected disabled>--Select Tools Type--</option>
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
                                        </select>
                                    </div>
                                    <div class="col-lg-4 p-2">
                                        <label for="phone_no">Phone Number</label>
                                        <input type="text" class="form-control" id="phone_no" name="phone_no" maxlength="10" placeholder="Enter phone number" required>
                                    </div>
                                    <div class="col-lg-4 p-2">
                                        <label for="check_out_date">Check Out Date</label>
                                        <input type="date" class="form-control" id="check_out_date" name="check_out_date" required>
                                    </div>
                                    <div class="col-lg-4 p-2">
                                        <label for="given_by">Given By</label>
                                        <input type="text" class="form-control" id="given_by" name="given_by" required>
                                    </div>
                                    <div class="col-lg-4 p-2">
                                        <label for="image">Upload Image</label>
                                        <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                                    </div>
                                </div>
                                <div class="card-footer text-center">
                                    <button type="submit" class="btn btn-primary w-50">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="container mt-5">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="h2" style="color:Green">Rental Manage Table</h3>
                            <div class="card-body">
                            </div>
                            <div class="table-responsive">
                                <table id="example" class="table p-2 table-bordered table-hover table-secondary">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Customer</th>
                                            <th>Tool</th>
                                            <th>Phone No</th>
                                            <th>Check Out Date</th>
                                            <th>Check In Date</th>
                                            <th>Given By</th>
                                            <th>Image</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT * FROM rental_tools";
                                        $result = $conn->query($sql);
                                        $counter = 1;
                                        while ($row = $result->fetch_assoc()) {
                                            // Disable the "Check In" button if the tool is already checked in
                                            $check_in_disabled = $row['check_in_date'] != NULL ? 'disabled' : '';
                                            echo "<tr>
                                    <td>" . $counter++ . "</td>
                                    <td>" . $row['username'] . "</td>
                                    <td>" . $row['tool_type'] . "</td>
                                    <td>" . $row['phone_no'] . "</td>
                                    <td>" . $row['check_out_date'] . "</td>
                                    <td>" . ($row['check_in_date'] ?? 'Not Checked In') . "</td>
                                    <td>" . $row['given_by'] . "</td>
                                    <td><img src='uploads/" . $row['image'] . "' width='85'></td>
                                    <td>
                                        <a href='?delete_id=" . $row['id'] . "' class='btn btn-danger btn-sm'>Delete</a>
                                        <a href='?checkin_id=" . $row['id'] . "' class='btn btn-success btn-sm' $check_in_disabled>Check In</a>
                                    </td>
                                </tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

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

</html>