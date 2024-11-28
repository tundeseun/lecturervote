<?php
header('Content-Type: application/json'); // Ensure JSON response
ini_set('display_errors', 0); // Suppress errors

require 'connect.php'; // Include database connection

// Get POST data
$data = json_decode(file_get_contents("php://input"), true);
if (!isset($data['id']) || !isset($data['status']) || !isset($data['reason'])|| !isset($data['email'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$id = intval($data['id']);
$status = intval($data['status']);
$reason = htmlspecialchars($data['reason'], ENT_QUOTES);
$email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
// Update the vote_request table
$query = "UPDATE vote_request SET status = ? WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $status, $id);

if ($stmt->execute()) {

    // $studentEmail = $email;
    echo json_encode(['success' => true, 'message' => 'Record updated but student email not found']);


    // if ($studentEmail) {
    //     // Send email to the student
    //     $subject = "Rejection of Your Vote Request";
    //     $message = "Dear Student,\n\nYour vote request has been rejected for the following reason:\n\n$reason\n\nIf you have any questions, please contact the administration.\n\nBest regards,\nThe Postgraduate College.";
    //     $headers = "From: abdulrahmonhammed1306@gmail.com";

    //     if (mail($studentEmail, $subject, $message, $headers)) {
    //         echo json_encode(['success' => true, 'message' => 'Record updated and email sent successfully']);
    //     } else {
    //         echo json_encode(['success' => false, 'message' => 'Record updated but failed to send email']);
    //     }
    // } else {
    //     echo json_encode(['success' => false, 'message' => 'Record updated but student email not found']);
    // }

    // $emailStmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update record']);
}

$stmt->close();
$conn->close();
?>
