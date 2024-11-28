<?php
header('Content-Type: application/json');
require_once 'connect.php';

$response = [];

try {
    // Consolidated SQL Query
    $query = "
        SELECT 
            SUM(CASE WHEN vr.status = 1 THEN 1 ELSE 0 END) AS pendingApprovalCount,
            SUM(CASE WHEN vr.status > 1 AND YEARWEEK(vr.timestamp, 1) = YEARWEEK(CURDATE(), 1) THEN 1 ELSE 0 END) AS approved_records_this_week,
            SUM(CASE WHEN vr.status = 1 AND YEARWEEK(vr.timestamp, 1) = YEARWEEK(CURDATE(), 1) THEN 1 ELSE 0 END) AS pending_approved_records_this_week,
            SUM(CASE WHEN vr.status > 1 THEN 1 ELSE 0 END) AS approved_count
        FROM vote_request vr
    ";

    $result = $conn->query($query);

    if ($result) {
        $data = $result->fetch_assoc();
        $response = [
            'pendingApprovalCount' => $data['pendingApprovalCount'] ?? 0,
            'approved_records_this_week' => $data['approved_records_this_week'] ?? 0,
            'pending_approved_records_this_week' => $data['pending_approved_records_this_week'] ?? 0,
            'approved_count' => $data['approved_count'] ?? 0,
        ];
    } else {
        throw new Exception("Query failed: " . $conn->error);
    }

    echo json_encode($response);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
}
$conn->close();
?>
