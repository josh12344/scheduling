<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Received Message and File</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400&display=swap">
    <style>
        /* Add Montserrat as the default font */
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f0f2f5;
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

        /* Messenger container styles */
        .messenger-container {
            width: 70%;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        /* Header styles */
        .header {
            background-color: #4e85e3;
            color: #fff;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 24px;
            margin: 0;
        }

        .header .settings-icon {
            font-size: 20px;
            cursor: pointer;
        }

        /* Message list styles */
        .message-list {
            max-height:100%;
            overflow-y: auto;
            padding: 20px;
            align-items: center;
        }

        /* Individual message styles */
        .message {
            background-color: #e1e8ed;
            color: #000;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 10px;
            max-width: 80%;
            align-items: center;
        }

    
      
    </style>
</head>
<body>
<body>
    <div class="messenger-container">
        <div class="header">
            <h1>Messages</h1>
            <span class="settings-icon">&#9881;</span>
        </div>
        <div class="container">
        <div class="message">
        
        <?php
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        // Database connection information
        $db_host = 'localhost';
        $db_user = 'root';
        $db_pass = '09102445986';
        $db_name = 'dummy_db';

        // Connect to the database
        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

        // Check the connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Handle the file and message received
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $sender = isset($_POST['sender']) ? htmlspecialchars($_POST['sender']) : 'Unknown Sender';
            $message = isset($_POST['msg']) ? htmlspecialchars($_POST['msg']) : 'No message';

            // Insert the message and file information into the database
            if (isset($_FILES["file"])) {
                $fileName = isset($_FILES["file"]["name"]) ? htmlspecialchars($_FILES["file"]["name"]) : 'No file name';
                $filePath = isset($_FILES["file"]["tmp_name"]) ? $_FILES["file"]["tmp_name"] : '';

                if (!empty($fileName) && !empty($filePath)) {
                    // Move the uploaded file to a specific directory if needed
                    $targetDirectory = 'uploads/'; // Specify the directory where you want to store uploaded files
                    $targetPath = $targetDirectory . $fileName;
                    move_uploaded_file($filePath, $targetPath);

                    // Insert data into the database
                    $sql = "INSERT INTO uploaded_files (sender, message, file_name, file_path) VALUES ('$sender', '$message', '$fileName', '$targetPath')";
                    if ($conn->query($sql) === TRUE) {
                        echo "<p>Message and file information saved to the database.</p>";
                    } else {
                        echo "Error: " . $sql . "<br>" . $conn->error;
                    }
                } else {
                    echo "<p>No file attached.</p>";
                }
            } else {
                echo "<p>No file attached.</p>";
            }
        } 

      // Retrieve messages from the database
$sql = "SELECT id, sender, message, status, file_name, file_path, timestamp FROM uploaded_files";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $message_id = $row['id']; // Use 'id' as the key
        $sender = htmlspecialchars($row['sender']);
        $message = htmlspecialchars($row['message']);
        $fileName = htmlspecialchars($row['file_name']);
        $status = $row['status'];
        $targetPath = isset($row['file_path']) ? htmlspecialchars($row['file_path']) : '';
        $timestamp = isset($row['timestamp']) ? htmlspecialchars($row['timestamp']) : 'No timestamp'; // Check for null value

        echo "<h2>Sender: $sender</h2>";
        echo "<p>Message: $message</p>";
      
     
if (!empty($fileName) && !empty($targetPath)) {
    echo "<p>File: <a href='$targetPath' target='_blank'>$fileName</a></p>";
    echo "<form method='post' action='delete_message.php'>";
    echo "<input type='hidden' name='message_id' value='$message_id'>"; // Pass the message ID to the delete script
    ?>
    <button onclick="return confirm('Are you sure you want to delete data?')" type='submit' name='deleteBtn'>Delete</button>
     <select name='status'>
        <option value="">Select Status</option>
        <option value='approved' <?= $status == 'approved' ? 'selected' : ''; ?>>Approve</option>
        <option value='declined' <?= $status == 'declined' ? 'selected' : ''; ?>>Decline</option>
    </select>
    <button onclick="return confirm('Are you sure to submit data?')" type='submit' name='updateMessage'>Submit</button>
    </form>
    <?php
} else {
    echo "<p>No file attached.</p>";
}

// Display the timestamp
echo "<p>Received on: $timestamp</p>"; // Display the timestamp or "No timestamp"
// ...

    }
} else {
    echo "<p>No messages found.</p>";
}
        $conn->close(); // Close the database connection
        ?>

        </div>
        
        <a href="calendarscheduling.php" class="button">Back to Home</a>
    </div>
    </div>
</body>
</html>
