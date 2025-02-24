<?php
session_start();
include_once('includes/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) {
    $toolId = intval($_POST['delete_id']);

    $conn = new mysqli("localhost", "root", "", "project1");

    if ($conn->connect_error) {
        echo json_encode(["status" => "error", "message" => "Database connection failed!"]);
        exit;
    }

    // Check if tool exists
    $checkSql = "SELECT tool_image FROM power_tools WHERE id = ?";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("i", $toolId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $imagePath = "uploads/" . $row['tool_image'];
        if (file_exists($imagePath)) {
            unlink($imagePath); // Delete the image
        }
    }
    $stmt->close();

    // Delete tool
    $deleteSql = "DELETE FROM power_tools WHERE id = ?";
    $stmt = $conn->prepare($deleteSql);
    $stmt->bind_param("i", $toolId);
    
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Tool deleted successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to delete tool."]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}
?>
