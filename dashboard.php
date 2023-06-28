<?php

session_start();

if (empty($_SESSION['admin']) || $_SESSION['admin'] != true) {

    Header("Location: login.php");


}

require "database_connection.php";
require "vendor/autoload.php";

use Dotenv\Dotenv;

use PHPMailer\PHPMailer\PHPMailer;
use Exception\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

if (isSet($_POST['title']) && isSet($_POST['body'])) {


    require "database_connection.php";
    require "vendor/autoload.php";

    $dbSearch = $databaseConnection->query("SELECT * FROM assinantes");
    $rowsNumber = $dbSearch->num_rows;

    for ($i = 0;  $i < $rowsNumber; $i++) {

        $userInfo = $dbSearch->fetch_array();

        $mail = new PHPMailer(true);
        $mail->CharSet = "UTF-8";
        $mail->Encoding = "base64";

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
            $mail->Subject = $_POST['title'];
            $mail->Body = <<<_HTML_
            <h2>Olá, $userInfo[1]!</h2>
            <br>
            <br>
            <h2>$_POST[body]</h2>
            <br>
            <br>
            <p>Caso queira cancelar a Newsletter, <a href="http://localhost/test/newslettercancel.php?hash=$userInfo[0]">clique aqui</a>.</p>
            _HTML_;
            $mail->AltBody = "";

            $mail->send();

            $emailSended = true;

        } catch(Exception $e) {

            print $e;
        }

    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <h2>Bem vindo, <?php print $_SESSION['username'];?>!</h2>

    <div class="menu">
        <a href="logout.php">
            <div class="btn logout.btn" >
                <span>Logout</span>    
            </div>
        </a>

        <a href="logout.php">
            <div class="btn logout.btn" >
                <span>Change user</span>    
            </div>
        </a>

    </div>
    <form method="POST">
        <label>Título: </label>
        <input type="text" name="title"><br>
        <label>Mensagem: </label>
        <textarea name="body" id="" cols="30" rows="10"></textarea><br>
        <input type="submit" value="Envar">

        <?php

        if (isSet($emailSended) && $emailSended == true) {
            print <<<_HTML_

            <div class="container">
                <h2>E-mail enviado com sucesso!</h2>
            </div>

            _HTML_;
        }

        ?>
    </form>
</body>
</html>