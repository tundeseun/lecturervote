<?php
$conn = new mysqli ("localhost", "root", "", "pginhouse");

if ($conn->connect_error) {
    die ("server not found" . $conn->connect_error);
}
?>