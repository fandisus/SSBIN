<?php
//$recipient = "great_beluluk@yahoo.com";
//$subject = 'PHPMailer test';
//$body = "Percobaan PHPMailer, bukan pake gmail, tapi pake icodeformoney.com";
namespace Trust;
class Mail {
  public static function sendMail($recipient, $subject, $body, $altBody='') {
    $mail = self::createPHPMailer();
    $mail->setFrom(PHPMAILER_FROM, PHPMAILER_NAME);
    //$mail->addReplyTo('replyto@example.com', 'First Last');
    $mail->addAddress($recipient);
    $mail->Subject = $subject;
    ////Read an HTML message body from an external file, convert referenced images to embedded,
    ////convert HTML into a basic plain-text alternative body
    //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
    $mail->Body = $body;
    //Replace the plain text body with one created manually
    $mail->AltBody = $altBody;
    //send the message, check for errors
    $mail->isHTML();
    return ($mail->send());
  }
  public static function createPHPMailer($isDebug=false) {
    //code comments: https://github.com/PHPMailer/PHPMailer/blob/master/examples/gmail.phps
    date_default_timezone_set('Asia/Jakarta');
    require DIR.'/phplib/PHPMailer/PHPMailerAutoload.php';
    $mail = new \PHPMailer;
    $mail->isSMTP();
    $mail->SMTPDebug = ($isDebug) ? 2 : 0;
    $mail->Debugoutput = 'html';
    $mail->Timeout = 15;
    $mail->Host = PHPMAILER_HOST;
    $mail->Port = PHPMAILER_PORT;
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth = true;
    $mail->Username = PHPMAILER_FROM;
    $mail->Password = PHPMAILER_PASS;

    return $mail;
  }
}
