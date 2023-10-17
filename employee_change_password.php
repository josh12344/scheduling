<?php
$servername = "localhost";
$username = "root";
$password = "09102445986";
$database = "dummy_db";

$connection = new mysqli($servername, $username, $password, $database);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Initialize the notification message as an empty string
$notification = "";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmNewPassword = $_POST['confirm_new_password'];

    // Perform server-side validation as needed
    if ($newPassword !== $confirmNewPassword) {
        $notification = "New passwords do not match. Please try again.";
    } else {
        // Assuming you have a table named 'register' with a 'password' column
        // Replace with your actual table and column names
        $hashedNewPassword = password_hash($newPassword, PASSWORD_BCRYPT); // Securely hash the new password

        // You can update the password directly without retrieving the username
        $updateQuery = "UPDATE register SET password = ?";
        $updateStmt = $connection->prepare($updateQuery);
        $updateStmt->bind_param("s", $hashedNewPassword);

        if ($updateStmt->execute()) {
            $notification = "Password changed successfully!";
        } else {
            $notification = "Error updating the password. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            background-image: url(cover.png);
            background-attachment: fixed;
            background-size: 100% 100%;
            background-repeat: no-repeat, repeat;
            text-align: center;
        }

        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
        }

        .form-group input {
            width: 95%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .submit-btn {
            width: 100%;
            background: #007BFF;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .submit-btn:hover {
            background: #0056b3;
        }
        .return-container {
            display: flex;
            flex-direction: column;
        }

        .return-link {
            display: inline-block;
            width: 95%; /* Set the width to 100% */
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
    </style>
</head>
<body>
<div class="container">
    <h2>Change Password</h2>
    <!-- Display the notification here -->
    <div><?php echo $notification; ?></div>
    <form action="" method="post" onsubmit="return validateForm()">
        <div class="form-group">
            <input type="password" class="form-control" id="current_password" name="current_password" placeholder="Current password:" required>
        </div>

        <div class="form-group">
            <input type="password" class="form-control" id="new_password" name="new_password" placeholder="New password:" required>
        </div>

        <div class="form-group">
            <input type="password" class="form-control" id="confirm_new_password" name="confirm_new_password" placeholder="Confirm new password:" required>
        </div>

        <button type="submit" class="submit-btn">Submit</button><br><br>
        <div class="return-container">
            <a href="employee_account.php" class="return-link">Return</a>
        </div>
    </form>
</div>

<script>
function validateForm() {
    var currentPassword = document.getElementById("current_password").value;
    var newPassword = document.getElementById("new_password").value;
    var confirmNewPassword = document.getElementById("confirm_new_password").value;

    if (currentPassword === "" || newPassword === "" || confirmNewPassword === "") {
        alert("Please fill in all the fields.");
        return false; // Prevent form submission
    }

    if (newPassword !== confirmNewPassword) {
        alert("New passwords do not match. Please try again.");
        return false; // Prevent form submission
    }

    return true; // Allow form submission
}
</script>

</body>
</html>