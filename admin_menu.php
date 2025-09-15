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
    <title>Admin menu</title>
</head>


<body>

    <h1>E-Sports league web portal - Admin Panel</h1>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
    <ul>
        <li><a href="search_form.php">Search for teams or participants</a></li>
        <li><a href="view_participants_edit_delete.php">View all participants to either edit or delete</a></li>
        <li><a href="index.html">Back to main site</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul> 
</body>
</html>