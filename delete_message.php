<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';


// Include the database connection configuration
include_once('database.php'); // Replace with the actual path to your database configuration file
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '09102445986';
$db_name = 'dummy_db';
// Connect to the database
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if (isset($_POST['deleteBtn'])) {
    $message_id = isset($_POST['message_id']) ? intval($_POST['message_id']) : 0;

    // Perform the delete operation
    $sql = "DELETE FROM uploaded_files WHERE id = $message_id";
    if ($conn->query($sql) === TRUE) {
        echo "Message deleted successfully.";
    } else {
        echo "Error deleting message: " . $conn->error;
    }
}

if (isset($_POST['updateMessage']))
{


    $message_id = mysqli_escape_string($conn, $_POST['message_id']);
    $status = mysqli_escape_string($conn, $_POST['status']);

    

    if (empty($message_id) || empty($status))
    {

        $_SESSION['status'] = 'Missing values.';

        header('Location: recievefile.php');
        exit();

    }

    $sql = "SELECT email, status FROM uploaded_files WHERE id = '$message_id' LIMIT 1";
    $resultEmail = mysqli_query($conn, $sql);

    if ($resultEmail)
    {
        $emails = mysqli_fetch_assoc($resultEmail);

        if ($emails)
        {

            $email = $emails['email'];
            $statusDb = $emails['status'];

        }
    }

    if ($statusDb == $status)
    {
        $_SESSION['status'] = 'Is already '.$status.'.';

        header('Location: recievefile.php');

        exit();
    }

    $query = "UPDATE uploaded_files SET status = '$status' WHERE id = '$message_id' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result)
    {
        if ($status == 'declined')
        {
            $messageM = 'sorry your leave request has been denied please pick another date to apply for a leave.';
        }
        else
        {
            $messageM = 'Your leave request has been Approved you may look at the schedule to confirm.';
        }

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
    $mail->Subject = 'Leave request';
    $mail->Body    = '<h3>'.$messageM.'</h3>';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
        echo "Successfully updated status.";

    }
    else
    {

        echo "Something went wrong.";

    }

}

// Close the database connection
$conn->close();

// Redirect back to the messages page or any other desired page
header("Location: recievefile.php"); // Change 'messages.php' to the appropriate page
?>
