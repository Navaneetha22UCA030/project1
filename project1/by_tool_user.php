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
</head>

<body>
    <?php include_once('includes/navbar.php'); ?>

    <div id="layoutSidenav">
        <!-- Include the Sidebar -->
        <?php include_once('includes/sidebar.php'); ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="row" id="table-manage">
                    <div class="col-lg-12">
                        <div class="container-fluid">
                            <h1 class="my-4">Power Tools</h1>
                            <div id="add-form">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover table-dark" id="datatablesSimple">
                                                <thead>
                                                    <tr>
                                                        <th>S.No</th>
                                                        <th>Tool Name</th>
                                                        <th>Tool Type</th>
                                                        <th>Tool Image</th>
                                                        <th>Tool Quantity Rate</th>
                                                        <th>Tool Sales Rate</th>
                                                        <th>Tool Brand</th>
                                                        <th>Action</th>
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
                                        <td><img src='uploads/" . $row['tool_image'] . "' alt='" . $row['tool_name'] . "' class='img' style='width: '100'></td>
                                        <td>" . $row['tool_quantity'] . "</td>
                                        <td>" . $row['tool_sales_rate'] . "</td>
                                        <td>" . $row['tool_sales_brand'] . "</td>
                                        <td>
                                        <a href='#' class='btn btn-danger deleteBtn' data-id='" . $row['id'] . "'>Buy</a>
                                        </td>
                                        </tr>";
                                                        }
                                                    } else {
                                                        echo "<tr><td colspan='7' class='text-center'>No tools found</td></tr>";
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
                </div>
            </main>
        </div>
</html>
<script src="js/script.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" crossorigin="anonymous"></script>
</body>