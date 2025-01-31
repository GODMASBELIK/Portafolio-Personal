<?php
// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';
require_once('classes/mensaje.php');

// Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);


$mensajeObj = [
    'destinatario' => [],
    'asunto' => '',
    'cuerpo' => '',
    'cc' => [],
    'cco' => [],
    'adjuntos' => []
];

// Input inicial
$mensajeObj['destinatario'][] = "Alexandrisman18@gmail.com";
$mensajeObj['asunto'] = "Vehiculo eliminado";
$mensajeObj['cuerpo'] = 

$option = null;
                try {
                    $mail = new PHPMailer(true);
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'Alexandrisman18@gmail.com';
                    $mail->Password = 'midy cjmy lyar qtvw';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                    $mail->Port = 465;

                    $mail->setFrom('Alexandrisman18@gmail.com', 'Mailer');
                    
                    foreach ($mensajeObj['destinatario'] as $dest) {
                        $mail->addAddress($dest);
                    }
                    foreach ($mensajeObj['cc'] as $cc) {
                        $mail->addCC($cc);
                    }
                    foreach ($mensajeObj['cco'] as $cco) {
                        $mail->addBCC($cco);
                    }
                    foreach ($mensajeObj['adjuntos'] as $adjunto) {
                        $mail->addAttachment($adjunto);
                    }
            
                    $mail->isHTML(true);
                    $mail->Subject = $mensajeObj['asunto'];
                    $mail->Body = $mensajeObj['cuerpo'];
            
                    $mail->send();
                    echo "Mensaje enviado con éxito.\n";
                } catch (Exception $e) {
                    echo "Error al enviar el mensaje: {$mail->ErrorInfo}\n";
                }


    //try {
    //Server settings
 //   $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
   // $mail->isSMTP();                                            //Send using SMTP
    //$mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
  //  $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
  //  $mail->Username   = 'Alexandrisman18@gmail.com';                     //SMTP username
  //  $mail->Password   = 'midy cjmy lyar qtvw';                               //SMTP password
  //  $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
  //  $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
//
  //  //Recipients
  //  $mail->setFrom('Alexandrisman18@gmail.com', 'Mailer');
  //  $mail->addAddress('joe@example.net', 'Joe User');     //Add a recipient
  //  $mail->addAddress('ellen@example.com');               //Name is optional
  //  $mail->addReplyTo('info@example.com', 'Information');
  //  $mail->addCC('cc@example.com');
  //  $mail->addBCC('bcc@example.com');
//
//
  //  //Content
  //  $mail->isHTML(true);                                  //Set email format to HTML
  //  $mail->Subject = 'Here is the subject';
  //  $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
  //  $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
//
  //  $mail->send();
  //  echo 'Message has been sent';

//catch (Exception $e) {
//    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
//}