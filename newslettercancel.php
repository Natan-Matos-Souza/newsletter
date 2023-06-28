<?php

require "database_connection.php";

require "vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

use Dotenv\Dotenv;

$userHash = filter_input(INPUT_GET, 'hash', FILTER_SANITIZE_SPECIAL_CHARS);

$dbSearch = $databaseConnection->query("SELECT * FROM assinantes WHERE userhash='$userHash'");

$rowsNumber = $dbSearch->num_rows;

if ($rowsNumber == 0) {

    print 'Hash inválido. Caso queira cancelar sua Newsletter, acesse o seu e-mail.';
    return;
}

$userInfo = $dbSearch->fetch_array();

$databaseConnection->query("DELETE FROM assinantes WHERE userhash='$userHash'");

$mail = new PHPMailer(true);

$mail->CharSet = 'UTF-8';
$mail->Encoding = 'base64';

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = $_ENV['hostEmail'];
    $mail->Password = $_ENV['emailPass'];
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    $mail->setFrom($_ENV['hostEmail']);
    $mail->addAddress($userInfo[2]);

    $mail->isHTML(true);
    $mail->Subject = "Sentiremos sua falta, $userInfo[1]";

    $mail->Body = <<<_HTML_
    <h2>Sentiremos sua falta $userInfo[1]!</h2>
    <br>
    <br>
    <h2>Você cancelou sua inscrição em nossa Newsletter. Caso queira participar novamente, <a href="http://localhost/test/index.php">clique aqui</a>.</h2>
    <br>
    <br>
    <h2>Atenciosamente,<br>Natan Matos!</h2>
    _HTML_;

    $mail->AltBody = '';

    $mail->send();

} catch(PHPMailerException $e) {
    print $e;
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancelar Newsletter</title>
</head>
<body>
    <h2>Sentiremos sua falta, <?php print $userInfo[1]?>!</h2>
    <p>Caso queira acessar nossa Newsletter novamente, enviamos um convite para o seu e-mail!</p>
</body>
</html>