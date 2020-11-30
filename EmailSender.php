<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

require "Database/Emails.php";


class EmailSender
{
    private $_host;
    private $_username;
    private $_password;

    public function __construct()
    {
        $this->_host = 'xxxx';
        $this->_username = 'xxx';
        $this->_password = 'xxx';
    }   

    public function respond($emails)
    {
        $emailsDatabase = new Emails();
        foreach ($emails as $email) {
            try {
                $mail = new PHPMailer;
              
                $mail->SMTPDebug = 2;                                     
                $mail->isSMTP();                                           
                $mail->Host       =  $this->_host;  
                $mail->SMTPAuth   = true;                   
                               
                $mail->Username   = $this->_username;         
                $mail->Password   =  $this->_password;                       
                $mail->SMTPSecure = 'tls';                                 
                $mail->Port       = 587;   
                $mail->setLanguage('pl', '/optional/path/to/language/directory/');   
                $mail->CharSet = 'UTF-8';                             
           
                $mail->setFrom('xxxxx', 'xx');
 
                $mail->isHTML(true);                                
                $mail->Subject = 'xxxx';
                $mail->Body    = 'xxx';
                $mail->AltBody = 'xxxx';
    
                $mail->send();
                $emailsDatabase->saveToDb($email);
              
                echo 'Message has been sent';
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }
    }
}
?>

