<?php
// Connect to your database (replace with your database credentials)
$servername = "localhost";
$username = "root";
$password = "09102445986";
$dbname = "dummy_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to retrieve user details (replace with your actual query)
$userID = 1; // Replace with the user's ID
$sql = "SELECT username, fullname, email FROM register ";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $username = $row["username"];
    $fullname = $row["fullname"];
    $email = $row["email"];
} else {
    // Handle the case where the user is not found
    $username = "";
    $fullname = "";
    $email = "";
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Account Details</title>
    <link rel="stylesheet" href="your-styles.css"> <!-- Add your custom CSS if needed -->
</head>
<style>
  body {
  margin: 0;
  font-family: Arial, Helvetica, sans-serif;
  overflow: hidden;
}


.topnav {
  overflow: hidden;
  background-color: royalblue;
}

.topnav a {
  float: left;
  color: #f2f2f2;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
  font-size: 17px;
}

.topnav a:hover {
  background-color: #ddd;
  color: black;
}

.img-account-profile {
    height: 10rem;
}
.rounded-circle {
    border-radius: 50% !important;
}
.card {
    box-shadow: 0 0.15rem 1.75rem 0 rgb(33 40 50 / 15%);
}
.card .card-header {
    font-weight: 500;
}
.card-header:first-child {
    border-radius: 0.35rem 0.35rem 0 0;
}
.card-header {
    padding: 1rem 1.35rem;
    margin-bottom: 0;
    background-color: rgba(33, 40, 50, 0.03);
    border-bottom: 1px solid rgba(33, 40, 50, 0.125);
}
.form-control, .dataTable-input {
    display: block;
    width: 100%;
    padding: 0.875rem 1.125rem;
    font-size: 0.875rem;
    font-weight: 400;
    line-height: 1;
    color: #69707a;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #c5ccd6;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    border-radius: 0.35rem;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}
.topnav a.split {
  float: right;
  
}
</style>
<body>
    
<div class="topnav">
<a href="employeepage.php" class="return-link">Return</a>
  <a href="employee_change_password.php">changepassword
  </a>
  <a href="logout.php" class="split">Logout</a>
</div>
<div class="container-xl px-4 mt-4">
   
    <hr class="mt-0 mb-4">
    <div class="row">
        <div class="col-xl-4">
         
            <!-- Account details card-->
            <div class="card mb-4">
          
                <div class="card-header">Account Details</div>
                <div class="card-body">
                <form>
    <!-- Form Group (username) -->
    <div class="mb-3">
        <label class="small mb-1" for="inputUsername">Username</label>
        <input class="form-control" id="inputUsername" type="text" placeholder="Enter your username" value="<?php echo $username; ?>" readonly>
    </div>
    <!-- Form Row -->
    <div class="row gx-3 mb-3">
        <!-- Form Group (first name) -->
        <div class="col-md-6">
            <label class="small mb-1" for="inputfullname">Full Name</label>
            <input class="form-control" id="inputfullname" type="text" placeholder="Enter your full name" value="<?php echo $fullname; ?>" readonly>
        </div>
    </div>
    <!-- Form Group (email address) -->
    <div class="mb-3">
        <label class="small mb-1" for="inputEmailAddress">Email address</label>
        <input class="form-control" id="inputEmailAddress" type="email" placeholder="Enter your email address" value="<?php echo $email; ?>" readonly>
    </div>
</form>
     
      </div>
      </div>
     </div>
    </div>
</div>
</body>
</html>
