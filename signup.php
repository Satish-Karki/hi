<?php
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "assignment_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

  
    $stmt = $conn->prepare("SELECT id FROM user_infos WHERE email = ?");
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Email already exists
            $message = "Email already exists";
        } else 
        {
           
            $stmt->close(); // Close the previous statement

            $stmt = $conn->prepare("INSERT INTO user_infos (email, password) VALUES (?, ?)");
            if ($stmt) {
                $stmt->bind_param("ss", $email, $password);
                if (!$stmt->execute()) {
                    $message = "Error: " . $stmt->error;
                } else {
                    $message = "Signup successful";
                }
                $stmt->close(); // Close the insertion statement
            } else {
                $message = "Failed to prepare statement: " . $conn->error;
            }
        }
    } else {
        $message = "Failed to prepare statement: " . $conn->error;
    }

    // Close the connection if necessary
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Signup Result</title>
    <style>
        body
        {
            height: 100vh;
            padding:0;
            margin:0;
            display: flex;
            justify-content:center;
            align-items: center;
            background-color:rgb(27, 3, 62);
        }
        .container
        {
            width: 20%;
            padding:30px 30px;
            
            text-align: center;
        
        }
      
       p
       {
            color : white;
            font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size:2em;
       }
        button {
            background-color:rgb(219, 0, 48);
            color: white;
            border: none;
            padding: 10px 20px;
        
            border-radius: 10px;
            font-size:1.4em;
            cursor: pointer;

        }
        button:hover
        {
            background: linear-gradient(to left,rgb(245, 28, 28), rgb(48, 48, 204));
        }
    </style>
</head>
<body>
    <div class="container">
        <p><?php echo htmlspecialchars($message); ?></p>
        <a href="index.html"><button>Go Back</button></a>
    </div>
</body>
</html>
