<?php
session_start();
include_once('includes/config.php');

if (strlen($_SESSION['adminid']) == 0) {
    header('location:logout.php');
    exit;
}

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Create Category
if (isset($_POST['submit'])) {
    $category = $_POST['category'];
    $description = $_POST['description'];
    $sql = mysqli_query($con, "INSERT INTO category(categoryName, categoryDescription, creationDate) VALUES('$category','$description', NOW())");

    if ($sql) {
        $_SESSION['msg'] = "Category Created!";
    } else {
        $_SESSION['msg'] = "Error creating category!";
    }
}

// Delete Category
if (isset($_GET['del']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $deleteQuery = mysqli_query($con, "DELETE FROM category WHERE id='$id'");

    if ($deleteQuery) {
        $_SESSION['delmsg'] = "Category deleted!";
    } else {
        $_SESSION['delmsg'] = "Error deleting category!";
    }

    header('location:category_admin.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard | Categories</title>
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
    <!-- Navbar -->
    <?php include_once('include/navbar-admin.php'); ?>

    <div id="layoutSidenav">
        <!-- Sidebar -->
        <?php include_once('include/sidebar-admin.php'); ?>

        <div id="layoutSidenav_content">
            <main class="p-4">
                <div class="container">
                    <div class="card">
                        <div class="card-header">
                            <h3>Category Management</h3>
                        </div>
                        <div class="card-body">
                            <?php if (isset($_SESSION['msg']) && $_SESSION['msg'] != "") { ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Success!</strong> <?php echo htmlentities($_SESSION['msg']); ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                                <?php unset($_SESSION['msg']); ?>
                            <?php } ?>

                            <?php if (isset($_SESSION['delmsg']) && $_SESSION['delmsg'] != "") { ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Deleted!</strong> <?php echo htmlentities($_SESSION['delmsg']); ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                                <?php unset($_SESSION['delmsg']); ?>
                            <?php } ?>

                            <form method="post">
                                <div class="mb-3">
                                    <label class="form-label">Category Name</label>
                                    <input type="text" name="category" class="form-control" placeholder="Enter category name" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description" rows="3"></textarea>
                                </div>

                                <button type="submit" name="submit" class="btn btn-primary">Create</button>
                            </form>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-header">
                            <h3>Manage Categories</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                            <table id="tableadmin" class="table display nowrap table-borderd table-striped table-bordered table-bordered table-hover table-secondary">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Category</th>
            <th>Description</th>
            <th>Creation Date</th>
            <th>Last Updated</th> <!-- NEW COLUMN -->
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $query = mysqli_query($con, "SELECT * FROM category ORDER BY id DESC");
        $cnt = 1;
        while ($row = mysqli_fetch_array($query)) {
        ?>
            <tr>
                <td><?php echo htmlentities($cnt); ?></td>
                <td><?php echo htmlentities($row['categoryName']); ?></td>
                <td><?php echo htmlentities($row['categoryDescription']); ?></td>
                <td><?php echo htmlentities($row['creationDate']); ?></td>
                <td>
                    <?php 
                    if ($row['updationDate'] != NULL) {
                        echo htmlentities($row['updationDate']); 
                    } else {
                        echo "Not Updated";
                    }
                    ?>
                </td>
                <td>
                    <a href="edit-category.php?id=<?php echo $row['id'] ?>" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <a href="category_admin.php?id=<?php echo $row['id'] ?>&del=delete"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Are you sure you want to delete?')">
                        <i class="bi bi-trash"></i> Delete
                    </a>
                </td>
            </tr>
        <?php $cnt++;
        } ?>
    </tbody>
</table>

                            </div>
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables Initialization -->
    <script>
        $(document).ready(function () {
            $('#categoryTable').DataTable({
                responsive: true,
                autoWidth: false,
                pageLength: 10,
                language: {
                    search: '🔍 _INPUT_',
                    searchPlaceholder: 'Search categories...',
                    lengthMenu: 'Show _MENU_ entries',
                    paginate: {
                        first: 'First',
                        last: 'Last',
                        next: '&rarr;',
                        previous: '&larr;'
                    }
                }
            });
        });
    </script>

</body>
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
</html>
