<?php
header('Content-Type: application/json'); // Ensure JSON response
ini_set('display_errors', 0); // Suppress errors

require 'connect.php'; // Include database connection

// Get POST data
$data = json_decode(file_get_contents("php://input"), true);
if (!isset($data['id']) || !isset($data['status'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$id = intval($data['id']);
$status = intval($data['status']);

// Update the vote_request table
$query = "UPDATE vote_request SET status = ? WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $status, $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Record updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update record']);
}

$stmt->close();
$conn->close();
?>
