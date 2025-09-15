<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register your interest</title>
</head>
<body>
    <?php
    //including connection variables  
    include 'dbconnect.php';

            try {
                $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password); //building a new connection object
                // set the PDO error mode to exception
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    // Get form data
                    $firstname = $_POST['firstname'];
                    $surname = $_POST['surname'];
                    $email = $_POST['email'];
                    $terms = isset($_POST['terms']) ? 1 : 0;
                    
                    // Validate required fields
                    if (empty($firstname) || empty($surname) || empty($email) || $terms == 0) {
                        echo "<p style='color: red;'>Please fill in all fields and accept the terms and conditions.</p>";
                        echo "<a href='register_form.html'>Back to registration form</a>";
                    } else {
                        // Insert into merchandise table
                        $stmt = $conn->prepare("INSERT INTO merchandise (firstname, surname, email, terms) VALUES (?, ?, ?, ?)");
                        $result = $stmt->execute([$firstname, $surname, $email, $terms]);
                        
                        if ($result) {
                            echo "<h2>Registration Successful!</h2>";
                            echo "<p>Thank you, $firstname $surname, for registering your interest in merchandise.</p>";
                            echo "<p>We'll contact you at $email with more information.</p>";
                            echo "<a href='index.html'>Back to home page</a>";
                        } else {
                            echo "<p style='color: red;'>Registration failed. Please try again.</p>";
                            echo "<a href='register_form.html'>Back to registration form</a>";
                        }
                    }
                } else {
                    echo "<p>Please use the registration form to register.</p>";
                    echo "<a href='register_form.html'>Go to registration form</a>";
                }

                }
            catch(PDOException $e)
                {
                echo $e->getMessage(); //If we are not successful we will see an error
                }
        ?>


</body>
</html>