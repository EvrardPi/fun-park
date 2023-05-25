<?php

// Define name spaces
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function mailer($mailOfReceiver, $titleOfMail, $corpsOfMail)
{
    require 'Includes/PHPMailer.php';
    require 'Includes/SMTP.php';
    require 'Includes/Exception.php';

    // Create instance of phpmailer
    $mail = new PHPMailer();

    // Set mailer to use smtp
    $mail->isSMTP();

    // define smtp host
    $mail->Host = "smtp.gmail.com";

    // enable smtp authentification
    $mail->SMTPAuth = "true";

    // set type of encryption (ssl/tls)
    $mail->SMTPSecure = "tls";

    // set port to connect smtp
    $mail->Port = "587";

    // set gmail username
    $mail->Username = "funpark91@gmail.com";

    // set gmail password
    $mail->Password = "Jesaispas4+";

    // set email subject
    $mail->Subject = $titleOfMail;

    // Set sender email
    $mail->setFrom("funpark91@gmail.com");

    // Email body
    $mail->Body = $corpsOfMail;

    // Add recipient
    $mail->addAddress($mailOfReceiver);

    // Finally send mail
    $mail->Send();

    // Closing smtp connection
    $mail->smtpClose();
}

function Reserve($StartTime, $EndTime, $Duration = "15")
{
    $ReturnArray = array();
    $StartTime = strtotime($StartTime); // Timestamp
    $EndTime = strtotime($EndTime); // Timestamp

    $AddMins = $Duration * 60;

    while ($StartTime <= $EndTime) {
        $ReturnArray[] = date("G:i", $StartTime);
        $StartTime += $AddMins;
    }
    return $ReturnArray;
}
