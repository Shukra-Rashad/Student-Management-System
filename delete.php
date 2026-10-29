<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: view.php");
        exit();
    } else {
        echo "Error deleting student: " . $conn->error;
    }

    $stmt->close();
} else {
    echo "Student ID not provided.";
}

$conn->close();
?>