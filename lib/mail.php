<?php
 function smtpmailer($to,$sujet,$body) {
	global $smtp_mail_error;

	$headers = array();
	$headers[] = "MIME-Version: 1.0";
	$headers[] = "Content-type: text/plain; charset=UTF-8";
	$headers[] = "From: [Aboo] <contact@aboo.fr>";
	$headers[] = "Bcc: Frédéric Meyrou <frederic@meyrou.com>";
	$headers[] = "Reply-To: [Aboo] <contact@aboo.fr>";
	$headers[] = "Return-Path:<contact@aboo.fr>";
	$headers[] = "Subject: {$sujet}";
	$headers[] = "X-Mailer: PHP/".phpversion();
	
	$mail = new PHPMailer();  // create a new object
	$mail->IsSMTP(); // enable SMTP
	$mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
	$mail->SMTPAuth = false;  // authentication enabled
	$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
	$mail->Host = 'smtp.gmail.com';
	$mail->Port = 465;
	$mail->Username = '';
	$mail->Password = '';
	$mail->SetFrom($from, $from_name);
	$mail->Subject = $subject;
	$mail->Body = $body;
	$mail->AddAddress($to);
	$headers   = array();

	if(!$mail->Send()) {
		$smtp_mail_error = $mail->ErrorInfo;
		return false;
	} else {
		$smtp_mail_error = null;
		return true;
	}
}
?>
