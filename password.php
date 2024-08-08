<?php
session_start();
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "assignment_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input_password = $_POST['password'];
    $email=$_SESSION['email'];
    $stmt= $conn->prepare("SELECT password FROM user_infos WHERE email = ?");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();
    }
    if (password_verify($input_password, $hashed_password)) {
        header("Location: welcome.html");
        exit();
    } else {
        echo "Invalid password";
    }
}

else {
        echo "No user found with this email";
    }
    $stmt->close();

?>