<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // path to PHPMailer

function sendRegistrationEmail($toEmail, $password, $loginLink) {
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.hostinger.com';  
        $mail->SMTPAuth   = true;
        $mail->Username   = 'support@ispsc-clinica.personatab.com';  // your gmail
        $mail->Password   = 'ispsc_Clinica1';    
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        //Recipients
        $mail->setFrom('support@ispsc-clinica.personatab.com', 'ISPSC Clinic Office');
        $mail->addAddress($toEmail);

        // Content
        $mail->isHTML(true);
        $mail->Subject = "Welcome to ISPSC-CLINICA!";

        $mail->Body = "
            <p>Dear Student,</p>
            
            <p>Welcome to <b>ISPSC-CLINICA</b>!</p>
            
            <p>We are pleased to inform you that your registration has been successfully completed. Below are your login credentials. Please keep them safe and confidential:</p>
            
            <p><b>Email:</b> {$toEmail}<br>
            <b>Password:</b> {$password}</p>
            
            <p>You may access your account by clicking the link below:</p>
            <p><a href='{$loginLink}'>Click here to login</a></p>
            
            <p>For your security, please do not share this information with anyone. If you did not initiate this registration, kindly contact the ISPSC Clinic Office immediately.</p>
            
            <br>
            <p>Best regards,<br>
            <b>ISPSC Clinic Office</b></p>
        ";


        $mail->send();
        return true;
    } catch (Exception $e) {
        return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
