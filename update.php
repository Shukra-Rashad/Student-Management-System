<?php
include 'db_connect.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$error = "";
$student = null;

if ($id <= 0) {
    die("Invalid student ID.");
}

// Fetch the existing student details
$stmt = $conn->prepare(
    "SELECT full_name, email, phone FROM students WHERE id = ?"
);
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$student = $result->fetch_assoc();
$stmt->close();

if (!$student) {
    die("Student not found.");
}

// Update the student when the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    if ($full_name === '' || $email === '' || $phone === '') {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        $stmt = $conn->prepare(
            "UPDATE students SET full_name = ?, email = ?, phone = ? WHERE id = ?"
        );
        $stmt->bind_param("sssi", $full_name, $email, $phone, $id);

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: view.php");
            exit();
        } else {
            $error = "Error updating student.";
            $stmt->close();
        }
    }

    // Keep the entered values in the form if validation fails
    $student['full_name'] = $full_name;
    $student['email'] = $email;
    $student['phone'] = $phone;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Update Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4">Update Student</h2>

    <?php if ($error !== ""): ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="full_name" class="form-label">Full Name</label>
            <input type="text" id="full_name" name="full_name"
                   class="form-control" required
                   value="<?php echo htmlspecialchars($student['full_name']); ?>">
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email"
                   class="form-control" required
                   value="<?php echo htmlspecialchars($student['email']); ?>">
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" id="phone" name="phone"
                   class="form-control" required
                   value="<?php echo htmlspecialchars($student['phone']); ?>">
        </div>

        <button type="submit" class="btn btn-success">Update Student</button>
        <a href="view.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

</body>
</html>

<?php
$conn->close();
?>