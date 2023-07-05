<?php

session_start();

if (empty($_SESSION['admin']) || $_SESSION != true) {

    Header("Location: login.php");
}


require "vendor/autoload.php";
require "database_connection.php";

use Dotenv\Dotenv;

$path = dirname(__FILE__);

$dotenv = Dotenv::createImmutable($path);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;
use PHPMailer\PHPMailer\SMTP;

if (isSet($_POST['email-title']) && isSet($_POST['email-content'])) {

    $emailTitle = $_POST['email-title'];

    $emailContent = $_POST['email-content'];

    $storeData = $databaseConnection->query("INSERT INTO emailhistory VALUES ('$emailTitle', '$emailContent')");


    //Envia email para todos usuários.

    
    $dbSearch = $databaseConnection->query("SELECT * FROM assinantes");
    $membersToSend = $dbSearch->num_rows;

    for ($i = 0; $i < $membersToSend; $i++) {

        $userInfo = $dbSearch->fetch_array();

        try {
            $mail = new PHPMailer(true);

            $mail->CharSet = "UTF-8";
            $mail->Enconding = 'base64';
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
            $mail->Subject = $emailTitle;

            $mail->Body = <<<_HTML_
            $emailContent
            _HTML_;

            $mail->send();

            $emailSended = true;

        } catch(PHPMailerException $e) {
            $emailSended = array(false, 'internal-problem');
        }
    } 
    
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,500;0,700;1,100;1,300;1,400;1,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style/sendemail.css">
    <title>Enviar E-mail</title>
</head>
<body>

    <header>
        <div class="nav-bar">
            <a href="logout.php">Logout</a>
        </div>
    </header>

    <main>
        <div class="form" id="form">
            <form method="POST">
                <div class="first-input-area">
                    <h2 class="label">Título:</h2>
                    <input type="text" name="email-title" class="first-input">
                </div>

                <div class="second-input-area">
                    <h2 class="label">Conteúdo:</h2>
                    <textarea name="email-content" class="second-input"></textarea>
                </div>

                <div class="submit-btn-area">
                    <input type="submit" class="submit-btn" value="Enviar">
                </div>
            </form>
        </div>
    </main>

    <section>
        <div class="email-test" id="email-test">
            <div class="email-title-area">
                <h2 class="email-title"></h2>
            </div>
            <hr>
            <div class="email-content-area">
                <div class="email-content"></div>
            </div>
        </div>
    </section>

    <script src="js/sendemail.js"></script>
    
</body>
</html>