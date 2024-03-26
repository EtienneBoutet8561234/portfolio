<?php
// Assuming PDO for database connection setup previously
$host = 'localhost'; // Typically 'localhost'
$dbname = 'hospital_db';
$user = 'postgres';
$password = '1234';
$dsn = "pgsql:host=$host;dbname=$dbname";
$db = new PDO($dsn, $user, $password);

// Handle the delete operation
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $db->prepare("DELETE FROM patient WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: admin.php"); // Refresh the page to reflect changes
}

$alertMessage = ''; // Initialize an empty message
// Handle the addition of a new patient (simplified version)
if (isset($_POST['add'])) {
    // Retrieve form data and sanitize
    $name = $_POST['name'];
    // Generate a random 3-letter code
    // Generate a random 3-letter code consisting only of uppercase letters
$code = '';
for ($i = 0; $i < 3; $i++) {
    $code .= chr(rand(65, 90)); // ASCII values for 'A' to 'Z' are 65 to 90
}

    $priority = $_POST['priority'];
    // Use the current server time as the arrival time
    $arrival_time = date('Y-m-d H:i:s'); // Format: YYYY-MM-DD HH:MM:SS

    $stmt = $db->prepare("INSERT INTO patient (name, code, priority, arrival_time) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$name, $code, $priority, $arrival_time])) {
        // Prepare a JavaScript alert() with the generated code if the patient is successfully added
        $alertMessage = "alert('Patient added with code: " . $code . "');";
    }
    header("Location: admin.php"); // Refresh to show the new patient
}

// Fetch patients ordered by priority and arrival time
$stmt = $db->prepare("SELECT * FROM patient ORDER BY priority DESC, arrival_time ASC");
$stmt->execute();
$patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Gestion des Patients</title>
</head>
<body>
    <h1>Gestion des Patients</h1>
    
    <!-- Add Patient Form -->
    <form action="admin.php" method="post">
        <input type="text" name="name" placeholder="Name" required>
        <input type="number" name="priority" placeholder="Priority" required min="1" max="5">
        <button type="submit" name="add">Add Patient</button>
    </form>

    <!-- List of Patients -->
    <h2>List of Patients</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Code</th>
                <th>Priority</th>
                <th>Arrival Time</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($patients as $patient): ?>
                <tr>
                    <td><?= htmlspecialchars($patient['name']) ?></td>
                    <td><?= htmlspecialchars($patient['code']) ?></td>
                    <td><?= htmlspecialchars($patient['priority']) ?></td>
                    <td><?= htmlspecialchars($patient['arrival_time']) ?></td>
                    <td><a href="admin.php?delete=<?= $patient['id'] ?>">✔</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php
    // If there's a message to display, output the JavaScript code to show the alert
    if (!empty($alertMessage)) {
        echo "<script>$alertMessage</script>";
    }
    ?>
</body>
</html>
