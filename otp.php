<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/SMTP.php';

session_start();


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "assignment_db";

// Create a new PHPMailer instance
$mail = new PHPMailer(true);

    // Server settings
    $mail->isSMTP(); // Set mailer to use SMTP
    $mail->Host       = 'smtp.gmail.com'; // Specify SMTP server
    $mail->SMTPAuth   = true; // Enable SMTP authentication
    $mail->Username   = 'satishkarki1000@gmail.com'; // SMTP username
    $mail->Password   = 'kybr zddx pggd wdlq'; // SMTP password (App Password if 2FA is enabled)
    $mail->SMTPSecure = 'ssl'; // Enable SSL encryption
    $mail->Port       = 465; // TCP port to connect to

    // Recipients
    $mail->setFrom('satishkarki1000@gmail.com', 'Your Name'); // Use the same email as Username
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = $_POST['email'];
        
        // Create a new database connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare and execute email validation query
        $stmt = $conn->prepare("SELECT id FROM user_infos WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if (!$stmt->num_rows > 0) {
            // Email does not exist in the database
            echo "<!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <title>Signup Result</title>
                <style>
                    body {
                        height: 100vh;
                        padding:0;
                        margin:0;
                        display: flex;
                        justify-content:center;
                        align-items: center;
                        background-image: url('1.png');
                        background-size: cover;
                    }
                    .container {
                        width: 20%;
                        padding:30px 30px;
                        text-align: center;
                        border-radius:10px;
                        background-color: rgb(48, 47, 47);
                    }
                    p {
                        color: white;
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
                    button:hover {
                        background: linear-gradient(to left,rgb(245, 28, 28), rgb(48, 48, 204));
                    }
                </style>
            </head>
            <body>
                <div class='container'>
                    <p>Invalid Email</p>
                    <a href='index.html'><button>Go Back</button></a>
                </div>
            </body>
            </html>";
            $stmt->close();
        } else {
            // Generate OTP
            $otp_code = strtoupper(substr(md5(rand()), 0, 6));
            $expires_at = date("Y-m-d H:i:s", strtotime("+2 minutes"));

            // Insert OTP into the database
            $stmt = $conn->prepare("INSERT INTO otp (email, otp_code, expires_at) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $email, $otp_code, $expires_at);

            if ($stmt->execute()) {
                // Send OTP via email
                $mail->addAddress($email); // Add recipient email
                $mail->isHTML(true); // Set email format to HTML
                $mail->Subject = 'Your OTP Code';
                $mail->Body    = "Your OTP code is <b>$otp_code</b>. It expires in 2 minutes.";

                $mail->SMTPDebug = 2; // Enable verbose debug output
                
                if ($mail->send()) {
                    echo "<!DOCTYPE html>
                    <html lang='en'>
                    <head>
                        <meta charset='UTF-8'>
                        <title>OTP Sent</title>
                        <style>
                            body {
                                height: 100vh;
                                padding:0;
                                margin:0;
                                display: flex;
                                justify-content:center;
                                align-items: center;
                                background-image: url('1.png');
                                background-size: cover;
                            }
                            .container {
                                width: 20%;
                                padding:30px 30px;
                                text-align: center;
                                border-radius:10px;
                                background-color: rgb(48, 47, 47);
                            }
                            p {
                                color: white;
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
                            button:hover {
                                background: linear-gradient(to left,rgb(245, 28, 28), rgb(48, 48, 204));
                            }
                        </style>
                    </head>
                    <body>
                        <div class='container'>
                            <p>OTP has been sent to your email.</p>
                            <a href='verify.html?email=" . urlencode($email) . "'><button>Verify OTP</button></a>
                        </div>
                    </body>
                    </html>";
                } else {
                    echo "Failed to send OTP.";
                }

                $stmt->close();
            } else {
                echo "Failed to generate OTP.";
            }
        }

        $conn->close();
    }

?>
