<?php
session_start();

//ensure users are logged in to access this page
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: admin_login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>View participants</title>
</head>
<body>
    <h1>View all of the participants for edit or delete</h1>
    <a href="admin_menu.php">Back to admin menu</a>
    <?php
        
    //including connection variables   
    include 'dbconnect.php';
        
        try {
            $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password); //building a new connection object
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Select all participants with their team information
            $stmt = $conn->prepare("
                SELECT p.id, p.firstname, p.surname, p.email, p.kills, p.deaths, t.name as team_name, t.location 
                FROM participant p 
                LEFT JOIN team t ON p.team_id = t.id 
                ORDER BY p.surname, p.firstname
            ");
            $stmt->execute();
            $participants = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (count($participants) > 0) {
                echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
                echo "<tr style='background-color: #f2f2f2;'>";
                echo "<th>ID</th><th>Name</th><th>Email</th><th>Kills</th><th>Deaths</th><th>Team</th><th>Location</th><th>Actions</th>";
                echo "</tr>";
                
                foreach ($participants as $participant) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($participant['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($participant['firstname'] . ' ' . $participant['surname']) . "</td>";
                    echo "<td>" . htmlspecialchars($participant['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($participant['kills'] ?? '0') . "</td>";
                    echo "<td>" . htmlspecialchars($participant['deaths'] ?? '0') . "</td>";
                    echo "<td>" . htmlspecialchars($participant['team_name'] ?? 'No Team') . "</td>";
                    echo "<td>" . htmlspecialchars($participant['location'] ?? 'N/A') . "</td>";
                    echo "<td>";
                    echo "<a href='edit_participant.php?id=" . $participant['id'] . "' style='margin-right: 10px;'>Edit</a>";
                    echo "<a href='delete.php?id=" . $participant['id'] . "' onclick='return confirm(\"Are you sure you want to delete this participant?\")' style='color: red;'>Delete</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No participants found.</p>";
            } 
            
            }
        catch(PDOException $e)
            {
            echo $e->getMessage(); //If we are not successful we will see an error
            }
        ?>


</body>
</html>