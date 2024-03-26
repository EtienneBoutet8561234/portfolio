<?php
// Start PHP session to hold refresh count (or any other temporary data)
session_start();

// Database connection details
$host = 'localhost'; // Typically 'localhost'
$dbname = 'hospital_db';
$user = 'postgres';
$password = '1234';
$dsn = "pgsql:host=$host;dbname=$dbname";

try {
    // Create a PDO instance as db connection to PostgreSQL
    $db = new PDO($dsn, $user, $password);
    
    // Set error mode
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $info = ""; // Initialize info message

    // Check if form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['userCode'])) {
        $userCode = $_POST['userCode'];

        // Fetch the specific patient's info and the count of patients before them
        $stmt = $db->prepare("WITH ranked_patients AS (
                                  SELECT name, code, ROW_NUMBER() OVER (ORDER BY priority DESC, arrival_time) as queue_position
                                  FROM patient
                              )
                              SELECT name, queue_position - 1 as people_in_front
                              FROM ranked_patients
                              WHERE code = :code");
        $stmt->execute(['code' => $userCode]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            // Calculate the wait time (5 minutes per person in front)
            $waitTime = $result['people_in_front'] * 5;
            $info = "Bonjour, " . htmlspecialchars($result['name']) . ".<br>Persons in front of you: " . $result['people_in_front'] . "<br>Estimated wait time: " . $waitTime . " minutes.";
        } else {
            // Patient not found
            $info = "<p>Code invalide ou patient introuvable.</p>";
        }
    }
} catch (PDOException $e) {
    // Handle connection errors
    $info = "Erreur de connexion : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login with Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            background-color: #f4f4f4;
        }
        #info {
            background-color: #dff0d8;
            color: #3c763d;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #d6e9c6;
            border-radius: 4px;
        }
    </style>
    <script>
        // Refresh the info every 15 seconds
        setTimeout(function() {
            window.location.reload(1);
        }, 15000);
    </script>
</head>
<body>
    
    <?php if (!empty($info)): ?>
        <div id="info">
            <?php echo $info; ?>
            <!-- Display a countdown timer for the next refresh -->
            <p>Refreshing in <span id="countdown">15</span> seconds...</p>
        </div>
    <?php endif; ?>

    <script>
        // Update the countdown timer every second
        var countdownElement = document.getElementById('countdown');
        var countdownTime = 15; // seconds
        setInterval(function() {
            countdownTime--;
            countdownElement.innerText = countdownTime;
        }, 1000);
    </script>
</body>
</html>
