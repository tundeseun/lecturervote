<?php
include 'conn.php'; 

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id'])) {
    $requestId = $data['id'];

    try {
        $query = "UPDATE vote_request SET status = -1 WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $requestId);  
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No rows updated.']);
        }
    } catch (mysqli_sql_exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    } finally {
        $conn->close();
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
