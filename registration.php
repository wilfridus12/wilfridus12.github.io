<?php
ini_set('display_errors', 0);

http_response_code(404);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if (isset($_POST["register"])) {
    $rank = $_POST["rank"];
    $model = $_POST["model"];
    $timing = $_POST["timing"];
    $name = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $repeat_password = $_POST["repeat_password"];

    if (empty($rank) || empty($model) || empty($timing) || empty($name) || empty($email) || empty($password) || empty($repeat_password)) {
        $error_message = "All fields are required.";

    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    } else if ($password !== $repeat_password) {
        $error_message = "Passwords do not match.";
    }

    if (!empty($error_message)) {
        echo '<div class="error">' . $error_message . '</div>';
    } else {
      
    
    

    $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);


    $encrypted_password = password_hash($password, PASSWORD_DEFAULT);

    // Database connection
    $conn = mysqli_connect("localhost", "root", "", "user");

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Insert data into the users table
    $sql = "INSERT INTO user (rank, model, timing, username, email, password, verificationcode, emailverifiedat, id_role) VALUES (?, ?, ?, ?, ?, ?, ?, NULL , 1)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("sssssss", $rank, $model, $timing, $name, $email, $encrypted_password, $verification_code);
        if ($stmt->execute()) {
            $mail = new PHPMailer(true);
            try {
                $mail->SMTPDebug = 0;
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );                
                $mail->Username = 'hansentanjaya44@gmail.com';
                $mail->Password = 'raja ibno owez canv'; 
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                $mail->setFrom('hansentanjaya44@gmail.com', 'Your Verification Email');
                $mail->addAddress($email, $name);
                $mail->isHTML(true);
                $mail->Subject = 'Email Verification';
                $mail->Body = '<p>Your verification code is: <b style="font-size: 30px;">' . $verification_code . '</b></p>';
                $mail->send();
                 
                header('Location: verficationcode.php');
                exit;
                
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            echo "Error: " . $stmt->error;
        }
    
        $stmt->close();
    } else {
        echo "Error in prepared statement: " . $conn->error;
    }

    $conn->close();

}

}
?>



<!DOCTYPE html>
<html>

<head>
    <title>Registration Page</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">



</head>

<body style="background-image: url('wangan_wiki.png')">
    <div class="container vh-100">
        <div class="row justify-content-center h-100">
            <div class="card w-50 my-auto shadow">
                <div class="card-header text-center bg-primary text-white">
                    <h2>Registration Form</h2>
                </div>
                <div class="card-body">

                    <form name="registrationForm" action="registration.php" method="post"
                        onsubmit="return validateForm();">
                        <div class="mb-3">
                            <label for="rank" class="form-label">Rank</label>
                            <input type="text" id="rank" class="form-control" name="rank" placeholder="Enter Rank"
                                required>
                            <?php if (!empty($error_message) && empty($rank)) {
            echo '<div class="error">' . $error_message . '</div>';
        } ?>


                        </div>
                        <div class="mb-3">
                            <label for="model" class="form-label">Model</label>
                            <input type="text" id="model" class="form-control" name="model" placeholder="Enter Model"
                                required>
                            <?php if (!empty($error_message) && empty($model)) {
            echo '<div class="error">' . $error_message . '</div>';
        } ?>


                        </div>
                        <div class="mb-3">
                            <label for="timing" class="form-label">Timing</label>
                            <input type="datetime-local" id="timing" class="form-control" name="timing"
                                placeholder="Enter Timing" required>
                            <?php if (!empty($error_message) && empty($timing)) {
            echo '<div class="error">' . $error_message . '</div>';
        } ?>


                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" id="username" class="form-control" name="username"
                                placeholder="Enter Username" required>
                            <?php if (!empty($error_message) && empty($name)) {
            echo '<div class="error">' . $error_message . '</div>';
        } ?>


                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" id="password" class="form-control" name="password"
                                placeholder="Enter Password" required>
                        </div>
                        <div class="mb-3">
                            <label for="repeat_password" class="form-label">Repeat Password</label>
                            <input type="password" id="repeat_password" class="form-control"
                                placeholder="Enter Repeat Password" name="repeat_password" required>
                            <?php if (!empty($error_message) && empty($repeat_password)) {
            echo '<div class="error">' . $error_message . '</div>';
        } ?>


                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" class="form-control" name="email" placeholder="Enter Email"
                                required>
                            <?php if (!empty($error_message) && empty($email)) {
            echo '<div class="error">' . $error_message . '</div>';
        } ?>


                        </div>
                        <div class="mb-3">
                            <input type="submit" class="btn btn-primary w-100" value="Register" name="register">
                        </div>
                    </form>
                </div>
                <footer class="text-center">
                    Already a member? <a href="login.php">Login here</a>
                </footer>
                <div class="card-footer text-right">
                    <small>&copy; Gabriel_600IT</small>
                </div>
            </div>
        </div>
    </div>



</body>


</html>