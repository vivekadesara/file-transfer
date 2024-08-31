<?php
$conn = new mysqli('localhost', 'root', '', 'file_sharing_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
