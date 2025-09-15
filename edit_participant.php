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
    <title>Update participants score</title>
</head>
<body>
<a href="admin_menu.php">Back to admin menu</a>
    <?php
        
        //including connection variables   
        include 'dbconnect.php';

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password); //building a new connection object
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            if($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                $id = $_POST['id'];
                $kills = $_POST['kills'];
                $deaths = $_POST['deaths'];
                
                $stmt = $conn->prepare("UPDATE participant SET kills = ?, deaths = ? WHERE id = ?");
                $result = $stmt->execute([$kills, $deaths, $id]);
                
                if ($result) {
                    echo "<h2>Participant updated successfully!</h2>";
                    echo "<p>Kills: $kills, Deaths: $deaths</p>";
                    echo "<a href='view_participants_edit_delete.php'>Back to participants list</a>";
                } else {
                    echo "<p style='color: red;'>Update failed. Please try again.</p>";
                    echo "<a href='view_participants_edit_delete.php'>Back to participants list</a>";
                }
            }
            else{
                if (isset($_GET['id'])) {
                    $id = $_GET['id'];
                    $stmt = $conn->prepare("SELECT * FROM participant WHERE id = ?");
                    $stmt->execute([$id]);
                    $participant = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if ($participant) {
                        $firstname = $participant['firstname'];
                        $surname = $participant['surname'];
                        $kills = $participant['kills'] ?? 0;
                        $deaths = $participant['deaths'] ?? 0;
                        $participant_id = $participant['id'];
                        
                        include "edit_participant_form.php";
                    } else {
                        echo "<p style='color: red;'>Participant not found.</p>";
                        echo "<a href='view_participants_edit_delete.php'>Back to participants list</a>";
                    }
                } else {
                    echo "<p style='color: red;'>No participant ID provided.</p>";
                    echo "<a href='view_participants_edit_delete.php'>Back to participants list</a>";
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