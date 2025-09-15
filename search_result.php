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
    <title>Search results</title>
    
</head>
<body>
<a href="admin_menu.php">Back to admin menu</a>
    <?php
        
            
            include 'dbconnect.php';
        
        try {
            $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            if ($_POST['participant'] == "1") {

                // Search for a participant
                $search_term = $_POST['firstname_surname'];
                $stmt = $conn->prepare("
                    SELECT p.id, p.firstname, p.surname, p.email, p.kills, p.deaths, t.name as team_name, t.location 
                    FROM participant p 
                    LEFT JOIN team t ON p.team_id = t.id 
                    WHERE p.firstname LIKE ? OR p.surname LIKE ?
                    ORDER BY p.surname, p.firstname
                ");
                $search_pattern = "%$search_term%";
                $stmt->execute([$search_pattern, $search_pattern]);
                $participants = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                if (count($participants) > 0) {
                    echo "<h2>Search Results for: " . htmlspecialchars($search_term) . "</h2>";
                    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
                    echo "<tr style='background-color: #f2f2f2;'>";
                    echo "<th>Name</th><th>Email</th><th>Kills</th><th>Deaths</th><th>Team</th><th>Location</th>";
                    echo "</tr>";
                    
                    foreach ($participants as $participant) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($participant['firstname'] . ' ' . $participant['surname']) . "</td>";
                        echo "<td>" . htmlspecialchars($participant['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($participant['kills'] ?? '0') . "</td>";
                        echo "<td>" . htmlspecialchars($participant['deaths'] ?? '0') . "</td>";
                        echo "<td>" . htmlspecialchars($participant['team_name'] ?? 'No Team') . "</td>";
                        echo "<td>" . htmlspecialchars($participant['location'] ?? 'N/A') . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<h2>No participants found for: " . htmlspecialchars($search_term) . "</h2>";
                    echo "<p>Please try a different search term.</p>";
                }
            }
            else{

                // Search for a team
                $team_name = $_POST['team'];
                $stmt = $conn->prepare("
                    SELECT t.id, t.name, t.location, 
                           COUNT(p.id) as member_count,
                           AVG(p.kills) as avg_kills,
                           AVG(p.deaths) as avg_deaths,
                           SUM(p.kills) as total_kills,
                           SUM(p.deaths) as total_deaths
                    FROM team t 
                    LEFT JOIN participant p ON t.id = p.team_id 
                    WHERE t.name LIKE ?
                    GROUP BY t.id, t.name, t.location
                ");
                $search_pattern = "%$team_name%";
                $stmt->execute([$search_pattern]);
                $team = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($team) {
                    echo "<h2>Team Search Results for: " . htmlspecialchars($team_name) . "</h2>";
                    echo "<h3>Team Information</h3>";
                    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
                    echo "<tr style='background-color: #f2f2f2;'>";
                    echo "<th>Team Name</th><th>Location</th><th>Members</th><th>Total Kills</th><th>Total Deaths</th><th>Avg Kills</th><th>Avg Deaths</th>";
                    echo "</tr>";
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($team['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($team['location']) . "</td>";
                    echo "<td>" . htmlspecialchars($team['member_count']) . "</td>";
                    echo "<td>" . htmlspecialchars(number_format($team['total_kills'] ?? 0, 1)) . "</td>";
                    echo "<td>" . htmlspecialchars(number_format($team['total_deaths'] ?? 0, 1)) . "</td>";
                    echo "<td>" . htmlspecialchars(number_format($team['avg_kills'] ?? 0, 1)) . "</td>";
                    echo "<td>" . htmlspecialchars(number_format($team['avg_deaths'] ?? 0, 1)) . "</td>";
                    echo "</tr>";
                    echo "</table>";
                    
                    // Get team members
                    $stmt = $conn->prepare("
                        SELECT p.id, p.firstname, p.surname, p.email, p.kills, p.deaths 
                        FROM participant p 
                        WHERE p.team_id = ?
                        ORDER BY p.surname, p.firstname
                    ");
                    $stmt->execute([$team['id']]);
                    $members = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    if (count($members) > 0) {
                        echo "<h3>Team Members</h3>";
                        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
                        echo "<tr style='background-color: #f2f2f2;'>";
                        echo "<th>Name</th><th>Email</th><th>Kills</th><th>Deaths</th>";
                        echo "</tr>";
                        
                        foreach ($members as $member) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($member['firstname'] . ' ' . $member['surname']) . "</td>";
                            echo "<td>" . htmlspecialchars($member['email']) . "</td>";
                            echo "<td>" . htmlspecialchars($member['kills'] ?? '0') . "</td>";
                            echo "<td>" . htmlspecialchars($member['deaths'] ?? '0') . "</td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "<p>No members found for this team.</p>";
                    }
                } else {
                    echo "<h2>No team found for: " . htmlspecialchars($team_name) . "</h2>";
                    echo "<p>Please try a different team name.</p>";
                } 
            }
            
               
            }
        catch(PDOException $e)
            {
                echo $e->getMessage(); //If we are not successful in connecting or running the query we will see an error
            }
        ?>


</body>
</html>