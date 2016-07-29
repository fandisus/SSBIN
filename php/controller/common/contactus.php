<?php
if (!count($_POST)) { include DIR.'/php/view/common/contactus.php'; die(); }

if ($_POST['a'] != 'contactus') die ('Service unavailable');

use Trust\JSONResponse;
use Trust\Forms;

$c = Forms::getPostObject('contact');
if (trim($c->name) == '') JSONResponse::Error('Please input your name');
if (!Forms::validateEmail($c->email)) JSONResponse::Error('Invalid email');
if (trim($c->subject) == '') JSONResponse::Error('Please fill in the subject');
if (trim($c->message) == '') JSONResponse::Error('Please input the message');

$message = "<h3>SSBIN Contact Us</h3>From: $c->name ($c->email)<br />".
  "Subject: $c->subject<br /><br />Message:<br />$c->message";

//handle contactus.
date_default_timezone_set('Asia/Jakarta');
require DIR.'/phplib/PHPMailer/PHPMailerAutoload.php';
$email = new \PHPMailer;
$email->From      = CONTACTUS_FROM;
$email->FromName  = CONTACTUS_FROMNAME;
$email->Subject   = 'Contact us from '.$c->name;
$email->Body      = $message;
$email->AltBody   = '';
$email->AddAddress(CONTACTUS_RECIPIENT);
$email->isHTML();
$res = $email->Send();

JSONResponse::Success(['message'=>'Message sent']);