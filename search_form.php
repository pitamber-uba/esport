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
    <title>Search</title>
</head>


<body>
    <a href="admin_menu.php">Back to admin menu</a>
    <h1>Search for participants or teams</h1>

    <h2>Search for an individual participant</h2>
    <form action="search_result.php" method="POST">
        <p>Participant firstname or surname</p>
        <input type="text" name="firstname_surname"><br>
        <!--leave this hidden input in. Use it to determine whether you are searching for a participant or a team -->
        <input type="hidden" name="participant" value="1">
        <input type = "Submit">

    </form>
    
    <h2>Search for a team</h2>
    <form action="search_result.php" method="POST">
        <p>Team name</p>
        <input type="text" name="team"><br>
        <input type = "Submit">

    </form>
</body>
</html>