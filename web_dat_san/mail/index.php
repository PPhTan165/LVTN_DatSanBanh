<?php
    require_once 'PHPMailer/src/Exception.php';
    require_once 'PHPMailer/src/PHPMailer.php';
    require_once 'PHPMailer/src/SMTP.php';
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    class Mailer{

//Create an instance; passing `true` enables exceptions
        public function sendMail($title,$content,$addressEmail){
            $mail = new PHPMailer(true);

            try {
                //Server settings
                $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                $mail->isSMTP();   
                $mail->CharSet = 'utf-8';

                //Send using SMTP
                $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = 'meollo74@gmail.com';                     //SMTP username
                $mail->Password   = 'chco ytbk tnvm ulbh';                               //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            
                //Recipients
                $mail->setFrom('meollo74@gmail.com', 'TOTTENHAM_FC');
                $mail->addAddress($addressEmail);     //Add a recipient
            
                //Content
                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->Subject = $title;
                $mail->Body    = $content;
                // $mail->AltBody = $content;
            
                $mail->send();
                echo 'Message has been sent';
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
}

}
?>