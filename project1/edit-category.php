<?php
session_start();
include_once('includes/config.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = mysqli_query($con, "SELECT * FROM category WHERE id='$id'");
    $row = mysqli_fetch_array($query);
}

if (isset($_POST['update'])) {
    $category = $_POST['category'];
    $description = $_POST['description'];

    $sql = mysqli_query($con, "UPDATE category SET 
        categoryName='$category', 
        categoryDescription='$description', 
        updationDate=NOW() 
        WHERE id='$id'");

    if ($sql) {
        $_SESSION['msg'] = "Category Updated!";
    } else {
        $_SESSION['msg'] = "Error updating category!";
    }

    header("Location: category_admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Category</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Category</h2>
        <form method="post">
            <div class="mb-3">
                <label class="form-label">Category Name</label>
                <input type="text" name="category" class="form-control" value="<?php echo htmlentities($row['categoryName']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="description"><?php echo htmlentities($row['categoryDescription']); ?></textarea>
            </div>
            <button type="submit" name="update" class="btn btn-primary">Update</button>
        </form>
    </div>
</body>
</html>
