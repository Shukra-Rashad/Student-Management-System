<?php

require_once "db_connect.php";

// Get student details from the form
$full_name = trim($_POST["full_name"] ?? "");
$email = trim($_POST["email"] ?? "");
$phone = trim($_POST["phone"] ?? "");

// Check whether all fields are filled
if ($full_name === "" || $email === "" || $phone === "") {
    exit("Please fill in all fields!");
}

// Check email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit("Invalid email address!");
}

// Check phone number
if (!preg_match('/^[0-9]{10}$/', $phone)) {
    exit("Phone number must contain 10 digits!");
}

// Prepare the SQL query
$stmt = $conn->prepare(
    "INSERT INTO students (full_name, email, phone)
     VALUES (?, ?, ?)"
);

// Attach the student details to the query
$stmt->bind_param("sss", $full_name, $email, $phone);

// Save the student in the database
if ($stmt->execute()) {
    echo "<script>
            alert('Student registered successfully!');
            window.location.href = 'form.html';
          </script>";
} else {
    echo "Registration failed. Please try again.";
}

// Close the connection
$stmt->close();
$conn->close();

?>