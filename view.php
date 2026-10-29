<?php
include 'db_connect.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$sql = "SELECT * FROM students
        WHERE full_name LIKE ? OR email LIKE ?
        ORDER BY id DESC";

$stmt = $conn->prepare($sql);

$searchTerm = "%" . $search . "%";
$stmt->bind_param("ss", $searchTerm, $searchTerm);

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

<title>View Students - Student Management System</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    .table thead th {
        background-color: #1E3A5F;
        color: white;
    }

    .btn-primary {
            background-color: #1E3A5F;
            color: white;
    }
</style>


</head>

<body>

<div class="container mt-5">

<h1 class="text-center mb-5"
    style="color: #1E3A5F;">
          Registered Students</h1>

          <form method="GET" action="view.php" class="mb-4">
    <div class="row g-2">

    <div class="col-md-8">
        <input
            type="text"
            name="search"
            class="form-control"
            placeholder="Search by name or email..."
            value="<?php echo htmlspecialchars($search); ?>"
        >
    </div>

    <div class="col-md-2 d-grid">
        <button type="submit" class="btn btn-primary">
            Search
        </button>
    </div>

    <div class="col-md-2 d-grid">
        <a href="view.php" class="btn btn-secondary">
            Clear
        </a>
    </div>

</div>

</form>


<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover">

        <thead>
        <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Created At</th>
        </tr>
        </thead>

        <tbody>

            <?php
            if ($result && $result->num_rows > 0) {

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                    echo "</tr>";
                }

            } else {
                echo "<tr><td colspan='5' class='text-center'>No students found.</td></tr>";
            }
            ?>

        </tbody>

    </table>
</div>
</div>

</body>
</html>

<?php
$conn->close();
?>