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

if (isset($_POST['otp_code']) && isset($_SESSION['email'])) {
    $otp = $_POST['otp_code'];
    $email = $_SESSION['email']; // Assuming email is stored in session

    // Retrieve OTP and check
    $stmt = $conn->prepare("SELECT otp_code, expires_at FROM otp WHERE email = ? ORDER BY id DESC LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result) {
        $otpRecord = $result->fetch_assoc();

        if ($otpRecord && $otpRecord['otp_code'] === $otp && $otpRecord['expires_at'] > date('Y-m-d H:i:s')) {
           header("Location:password.html");
        } else {
            echo "Invalid or expired OTP.";
        }
    
} else {
    // OTP or email not set
    echo "Please enter the OTP.";
}}


$conn->close();
?>
