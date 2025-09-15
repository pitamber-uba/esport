<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Delete participant</title>
</head>
<body>
    <?php
       
    include 'dbconnect.php';

            try {
                $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password); //building a new connection object
                // set the PDO error mode to exception
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                if (isset($_GET['id'])) {
                    $id = $_GET['id'];
                    
                    // First, get participant details for confirmation
                    $stmt = $conn->prepare("SELECT firstname, surname FROM participant WHERE id = ?");
                    $stmt->execute([$id]);
                    $participant = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if ($participant) {
                        // Delete the participant
                        $stmt = $conn->prepare("DELETE FROM participant WHERE id = ?");
                        $result = $stmt->execute([$id]);
                        
                        if ($result) {
                            echo "<h2>Participant deleted successfully!</h2>";
                            echo "<p>" . htmlspecialchars($participant['firstname'] . ' ' . $participant['surname']) . " has been removed from the database.</p>";
                            echo "<a href='view_participants_edit_delete.php'>Back to participants list</a>";
                        } else {
                            echo "<p style='color: red;'>Delete failed. Please try again.</p>";
                            echo "<a href='view_participants_edit_delete.php'>Back to participants list</a>";
                        }
                    } else {
                        echo "<p style='color: red;'>Participant not found.</p>";
                        echo "<a href='view_participants_edit_delete.php'>Back to participants list</a>";
                    }
                } else {
                    echo "<p style='color: red;'>No participant ID provided.</p>";
                    echo "<a href='view_participants_edit_delete.php'>Back to participants list</a>";
                }

                }
            catch(PDOException $e)
                {
                echo "<p style='color: red;'>Database error: " . $e->getMessage() . "</p>";
                echo "<a href='view_participants_edit_delete.php'>Back to participants list</a>";
                }

        
        
        ?>


</body>
</html>