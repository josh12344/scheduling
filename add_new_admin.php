<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


require 'vendor/autoload.php';

$host = "localhost"; 
$username = "root"; 
$password = "09102445986"; 
$database = "dummy_db"; 


$conn = mysqli_connect($host, $username, $password, $database);


if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
if (isset($_POST["submit"])) {
    $fullName = $_POST["fullname"];
    $username = $_POST["username"]; 
    $email = $_POST["email"];
    $password = $_POST["password"];
    $passwordRepeat = $_POST["repeat_password"];
    $token = md5(12);
    
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
 
    $errors = array();
    
    if (empty($fullName) OR empty($username) OR empty($email) OR empty($password) OR empty($passwordRepeat)) {
     array_push($errors, "All fields are required");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
     array_push($errors, "Email is not valid");
    }
    if (strlen($password) < 8) {
     array_push($errors, "Password must be at least 8 characters long");
    }
    if ($password !== $passwordRepeat) {
     array_push($errors, "Password does not match");
    }
 
  
    $sql = "SELECT * FROM admin WHERE email = '$email' OR username = '$username'";
    $result = mysqli_query($conn, $sql);
 
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
 
    $rowCount = mysqli_num_rows($result);
 
    if ($rowCount > 0) {
     array_push($errors, "Email or Username already exists!");
    }
 
    if (count($errors) > 0) {
     foreach ($errors as $error) {
         echo "<div class='alert alert-danger'>$error</div>";
     }
    } else {
     // Insert the registration data into the database
     //Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'easternsamaroras@gmail.com';                     //SMTP username
    $mail->Password   = 'yoqlvwxkbnmznclt';                               //SMTP password
    $mail->SMTPSecure = 'ssl';            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('easternsamaroras@gmail.com');
    $mail->addAddress($email);     //Add a recipient

    //Attachments

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Account verification';
    $mail->Body    = '<a href="http://localhost/schedule/verify_admin.php?token='.$token.'">Click here to verify email.</a>';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
     $sql = "INSERT INTO admin (fullname, username, email, password, email_verify) VALUES (?, ?, ?, ?, ?)";
     $stmt = mysqli_stmt_init($conn);
 
     if (mysqli_stmt_prepare($stmt, $sql)) {
         mysqli_stmt_bind_param($stmt, "sssss", $fullName, $username, $email, $passwordHash, $token);
         mysqli_stmt_execute($stmt);
         echo "<div class='alert alert-success'>Check Email For verification Code.</div>";
     } else {
         die("Something went wrong");
     }
    }
 }
 

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add new admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <?= $_SESSION['status'] ?? ''; ?>
        <form action="add_new_admin.php" method="post">
            <div class="form-group">
                <input type="text" class="form-control" name="fullname" placeholder="Full Name:">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="username" placeholder="Username:">
            </div>
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="Email:">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password:">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="repeat_password" placeholder="Repeat Password:">
            </div>
            <div class="form-btn" >
                <input type="submit" class="btn btn-primary" value="Register" name="submit"><br><br>
                <a href="my_account.php" class="return-link">Return</a>

            </div>
        </form>
        <div>
      </div>
    </div>
    <style>
body {  
  background-image: url(cover.png);
 background-attachment: fixed;
 background-size: 100% 100% ;
  background-repeat: no-repeat, repeat;
 
  text-align: center;
  
}
.return-container {
        display: flex;
        flex-direction: column;
      }

      .return-link {
        display: inline-block;
    
       width: fit-content;
       height: 40px;
        background: #007BFF;
        color: #fff;
        text-align: center;
        padding: 10px;
        border-radius: 5px;
        text-decoration: none;
      }

      .return-link:hover {
        background: #0056b3;
      }

</style>
</body>
</html>