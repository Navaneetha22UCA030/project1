<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project1";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT * FROM power_tools";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error fetching data: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Admin Manage</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet" />
</head>

<body>
    <?php include_once('includes/navbar.php'); ?>

    <div id="layoutSidenav">
        <!-- Include the Sidebar -->
        <?php include_once('includes/sidebar.php'); ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluied mx-5 my-5 mb-5">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-dark" id=example>
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Customer</td>
                                    <th>Tools</td>
                                    <th>Phone_no</td>
                                    <th>Give Date</td>
                                    <th>check_in_date</td>
                                    <th>Given_by</td>
                                    <th>Image</td>
                                    <th>Action</td>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                $sql = "SELECT * FROM rental_tools";
                                $counter = 1;
                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>
                                <td>" . $counter++ . "</td>
                                <td>" . $row['username'] . "</td>
                                <td>" . $row['tool_type'] . "</td>
                                <td>" . $row['phone_no'] . "</td>
                                <td>" . $row['check_out_date'] . "</td>
                                <td>" . $row['check_in_date'] . "</td>
                                <td>" . $row['given_by'] . "</td>
                                <td><img src='uploads/" . $row['image'] . "' alt='Tool Image' width='85' hight='50'></td>
                                <td><a href='#' class='btn btn-danger deleteBtn' data-id='" . $row['id'] . "'>Delete</a></td>
                            </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='8'>No records found.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
</html>
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
<script src="js/script.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
<script src="js/datatables-simple-demo.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js" crossorigin="anonymous"></script>
</body>