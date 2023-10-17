<?php
session_start();
if (isset($_SESSION["user"])) {
     // Get the user's name from the session
     $loggedInFullName = $_SESSION["fullname"];
   header("Location: employeepage.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <?php
        if (isset($_POST["login"])) {
           $email = $_POST["email"];
           $password = $_POST["password"];
            require_once "database.php";
            $sql = "SELECT * FROM register WHERE email = '$email'";
            $result = mysqli_query($conn, $sql);
            $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
            if ($user) {
                if (password_verify($password, $user["password"])) {
                    // Check if the email is verified (status = 1)
                    if ($user['email_status'] == 1) {
                        // Store user data in session variables
                        $_SESSION["user_id"] = $user['id']; // Store the user's ID in the session
                        $_SESSION['email'] = $user['email'];
                        $_SESSION["fullname"] = $user["fullname"];
                        header("Location: employeepage.php");
                        die();
                    } else {
                        echo "<div class='alert alert-danger'>Your account is not verified. Please check your email for verification instructions.</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>Password does not match</div>";
                }
            }
        }
        ?>

<style>
body {
  background-image: url(cover.png);
 background-attachment: fixed;
 background-size: 100% 100% ;
  background-repeat: no-repeat, repeat;
 
  text-align: center;
  
}
</style>
      <form action="login.php" method="post">
        <h1>USER </h1>
        <div class="form-group">
            <input type="email" placeholder="Enter Email:" name="email" class="form-control">
        </div>
        <div class="form-group">
            <input type="password" placeholder="Enter Password:" name="password" class="form-control">
        </div>
        <div class="form-btn" a href="calendarscheduling.php"></a>
        <input type="submit" value="login" name="login" class="btn btn-primary">
           
        </div>
      </form>
      <div><p>Log-in as           <a href="adminlogin.php">     Administrator</a></p></div>
     <div><p>Not registered yet? <a href="registration.php">Register Here    </a></p></div>
    </div>
</body>
</html>
