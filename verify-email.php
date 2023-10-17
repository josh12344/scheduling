<?php

require 'db-connect.php';

if (isset($_GET['token']))
{
    $token = mysqli_escape_string($conn, $_GET['token']);

    if (empty($token)){
        $_SESSION['status'] = 'Token not valid.';
        header('Location: registration.php');
        exit();
    }

    $query = "SELECT email_verification, email_status FROM register WHERE email_verification = '$token';";
    $checkValidToken = mysqli_query($conn, $query);

    if (mysqli_num_rows($checkValidToken) > 0)
    {
        $row = mysqli_fetch_assoc($checkValidToken);
        
        if ($row['email_status'] == 0)
        {
            $result = mysqli_query($conn, "UPDATE register SET email_status = '1' WHERE email_verification = '$token' LIMIT 1;");

            if ($result)
            {
                $_SESSION['status'] = 'Email Successfully verified.';
                mysqli_close($conn);
                header('Location: login.php');
                exit();
            }
            else
            {
                $_SESSION['status'] = 'Something went wrong while verifying email.';
                header('Location: registration.php');
                exit();
            }

        }
    }
    else
    {
        $_SESSION['status'] = 'No token value found.';
        header('Location: registration.php');
        exit();
    }

}
else
{
    $_SESSION['status'] = 'No token value found.';
        header('Location: registration.php');
        exit();
}