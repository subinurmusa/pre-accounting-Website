<?php

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor\autoload.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

/* try { */
    //Server settings
  //  $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'officialfinancetech74@gmail.com';                     //SMTP username
    $mail->Password   = 'vvzm tihi lbjx iyxw';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->CharSet = 'UTF-8';

    // you can open from here ******
/*     $mail->setFrom('subinurmusa10@gmail.com', 'Finelogic  Ön Muhasebe Programı');
    $mail->addAddress('safiyehalike@gmail.com', 'sefiye');     //Add a recipient
  //  $mail->addAddress('ellen@example.com');               //Name is optional
   $mail->addReplyTo('info@example.com', 'Information');
//     $mail->addCC('cc@example.com');
  //  $mail->addBCC('bcc@example.com');
 
    //Attachments
    $mail->addAttachment('photoes\animasyon.PNG', "fotoraf ismi");         //Add attachments
  //  $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Kritik Stok Seviyesi'.$data;
    $mail->Body    = 'Depo lardaki urün : <b>'.$data.print_r($data).'</b>  nün stok seviyesi '.$data.' dikkate alınmasını talepediyoruz';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send(); */


 /*    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
} */
?>