
<?php 

session_start();
// Database connection (replace with your credentials)
$servername = "localhost";
$username = "root";
$password = "09102445986";
$dbname = "dummy_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400&display=swap" rel="stylesheet">
    <title>File Attachment</title>
    <style>
        /* Add Montserrat as the default font */
        body, input, textarea {
            font-family: 'Montserrat', sans-serif;
        }

        /* Apply background gradient */
        body {
            background-image: url(cover.png);
            background-size: cover; /* Adjust as needed to fit your design */
    background-repeat: no-repeat;
    background-position: center center;
    margin: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

        /* Form styles */
        form {
            background: #222;
            width: 650px;
            height: 450px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
        }

        /* Input styles */
        input[type="text"], textarea, input[type="file"] {
            border: none;
            background: whitesmoke;
            padding: 0.8em;
            outline: none;
            color: black;
            width: 500px;
            margin-bottom: 10px;
        }
        
        /* Submit button styles */
        input[type="submit"] {
            background: royalblue;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
        }

        /* Home button styles */
        .button {
            background: royalblue;
            color: #fff;
            text-decoration: none;
            margin-top: 10px;
            display: inline-block;
            border-radius: 5px;
            cursor: pointer;
            padding: 10px 20px;
        }

        /* Additional styles for your form */
        h1 {
            font-size: 30px;
            color: royalblue;
        }
    </style>
</head>
<body>
    

<form action="attachfile.php" method="post" enctype="multipart/form-data">
    <h1>Leave Request</h1>
    <input type="text" id="sender" name="sender" readonly value="<?= $_SESSION['fullname'] ?? ''; ?>" placeholder="Sender">
    <textarea id="chatMessage" placeholder="Message" name="msg" required></textarea>
    <input type="file" name="file" id="file">
    <input type="hidden" name="email" value="<?= $_SESSION['email'] ?>">

    <script>
        document.getElementById("sender").addEventListener("input", function() {
            document.getElementById("hidden_sender").value = this.value;
        });
    </script>

    <input type="submit" value="Send">
    <a href="employeepage.php" class="button">Home</a>
</form>

</body>
</html>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$sender = isset($_POST['sender']) ? htmlspecialchars($_POST['sender']) : 'unknown sender';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve sender's name from the hidden input field
    if (isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
        // Handle file upload
        $uploadDir = "uploads";
        $uploadFile = $uploadDir . basename($_FILES["file"]["name"]);

        if (move_uploaded_file($_FILES["file"]["tmp_name"], $uploadFile)) {
            // File uploaded successfully

            // Sanitize inputs
            $fileName = htmlspecialchars($_FILES["file"]["name"]);
            $filePath = htmlspecialchars($uploadFile);

            
            $message = isset($_POST['msg']) ? htmlspecialchars($_POST['msg']) : '';
            $email = $_POST['email'];

            // Prepare and execute an SQL insert query
            $sql = "INSERT INTO uploaded_files(file_name, file_path, sender, email, message) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $fileName, $filePath, $sender, $email, $message);
            
            if ($stmt->execute()) {
                // Display a success message using JavaScript
                echo '<script>alert("File sent successfully!");</script>';
            } else {
                // Log the error and display a user-friendly message
                error_log("Error executing SQL query: " . $stmt->error);
                echo "An error occurred while processing your request.";
            }

            $stmt->close();
            $conn->close();
        } else {
            echo "Error uploading file.";
        }
    } else {
        echo "No file uploaded or an error occurred during upload.";
    }
}
?>