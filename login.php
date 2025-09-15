<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    
</head>
<body>
    <?php
        
        include 'dbconnect.php';
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            try {
                $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $username_input = $_POST['username'];
                $password_input = $_POST['password'];
                $stmt = $conn->prepare("SELECT id, username, password FROM user WHERE username = ?");
                $stmt->execute([$username_input]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($user && $user['password'] === $password_input) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['logged_in'] = true;
                    
                    header("Location: admin_menu.php");
                    exit();
                } else {
                    echo "<p style='color: red;'>Invalid username or password. Please try again.</p>";
                    echo "<a href='admin_login.html'>Back to login</a>";
                }

                
                }
            catch(PDOException $e)
                {
                echo $e->getMessage();
                }
        }
        else{
            echo "<p>Please use the login form to access this page.</p>";
            echo "<a href='admin_login.html'>Go to login form</a>";
        }
        ?>


</body>
</html>