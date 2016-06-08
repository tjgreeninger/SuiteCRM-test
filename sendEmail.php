<?php 

require_once('include/SugarPHPMailer.php'); 

$emailObj = new Email(); 
$defaults = $emailObj->getSystemDefaultEmail(); 

$mail = new SugarPHPMailer(); 
$mail->setMailerForSystem(); 
$mail->From = $defaults['email']; 
$mail->FromName = $defaults['name']; 
$mail->Subject = 'Duplicate contacts'; 
$mail->Body = 'These contacts have duplicate phone numbers'; 
$mail->prepForOutbound(); 
$mail->AddAddress('tjgreeninger@gmail.com'); 
@$mail->Send();
?>
