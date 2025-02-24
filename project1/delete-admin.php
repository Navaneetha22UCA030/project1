<?php
// delete-admin.php
if (isset($_POST['id'])) {
    $tool_id = $_POST['id'];

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "project1";

    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Delete query
    $sql = "DELETE FROM power_tools WHERE id = $tool_id";

    if (mysqli_query($conn, $sql)) {
        echo "Tool deleted successfully!";
    } else {
        echo "Error deleting tool: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>
