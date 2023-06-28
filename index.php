<?php


//Importando classes.
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use Exception\PHPMailer\Exception;

use Dotenv\Dotenv;

if (isSet($_POST['username']) && isSet($_POST['email'])) {


    $userName = htmlspecialchars(trim($_POST['username']));

    $userEmail = htmlspecialchars(trim($_POST['email']));


    function sanitizedString($string) {

        $dirtyCharacters = array('\\', ';', '-', '=', ',', '>', '<');

        $replaceFor = array('', '', '', '', '', '', '');

        $sanitizedString = str_replace($dirtyCharacters, $replaceFor, $string);

        return $sanitizedString;

    }


    $userName = sanitizedString($userName);

    if (filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {

        $userEmail = sanitizedString($userEmail);

    } else {

        print "Email inválido!";

    }


    require "database_connection.php";
    
    $userHash = rand(1,100000);

    $userHash = password_hash($userHash, PASSWORD_BCRYPT);


    $databaseConnection->query("INSERT INTO assinantes VALUES ('$userHash', '$userName', '$userEmail')");




    require "vendor/autoload.php";

    //Cria uma nova instância da classe PHPMailer.
    $mail = new PHPMailer(true);
    //Coloca os caracteres UFT-8.
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = "base64";

    try {
        $mail->isSMTP(); //Configura o e-mail para SMTP.
        $mail->Host = 'smtp.gmail.com'; //Host do provedor de e-mail.
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['hostEmail']; //Informações da conta e-mail.
        $mail->Password = $_ENV['emailPass']; //Informações da senha e-mail.
        $mail->SMTPSecure= 'ssl'; //Utilizando SSL.
        $mail->Port = 465;

        $mail->setFrom($_ENV['hostEmail']); //Remetente.
        $mail->addAddress($userEmail); //Destinatário.

        $mail->isHTML(true); //Informa se o e-mail contém HTML.
        $mail->Subject = "Seja bem-vindo, $userName"; //Título do e-mail.

        $mail->Body = <<<_HTML_
        <h1>Olá, $userName!</h1>
        <br>
        <br>
        <h2>Você foi cadastrado em nossa Newsletter com sucesso!</h2>
        <br>
        <br>
        <h2>Atenciosamente,<br>Natan Matos!</h2>
        <br>
        <br>
        <p>Caso você queira cancelar a Newsletter, <a href="http://localhost/test/newslettercancel.php?hash=$userHash">clique aqui</a>.</p>
        _HTML_;
        $mail->AltBody = "Olá, Natan Matos! Estou testando o PHPMailer!"; //Mensagem alternativa caso não haja suporte ao HTML.

        $mail->send(); //Envia a mensagem.

        $emailSended = true;



    } catch (Exception $e) {
        print "O e-mail não foi enviado. Motivo: $e";
    }
}





?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/index.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,500;0,700;1,100;1,300;1,400;1,500&display=swap" rel="stylesheet">
    <title>Newsletter</title>
</head>
<body>


    <div class="container">
        <div class="page-logo">
            <img class="logo" src="assets/logo-light-mode.png" alt="Page logo">
        </div>

        <div class="title-area">
            <h2 class="page-title" >Cadastre-se em nossa Newsletter!</h2>
        </div>
    </div>
    
    <div class="form-area">

        <form method="POST" class="form">

            <div class="username-input-area">
                <label>Nome: </label>
                <input class="text-input" id="name-input" type="text" name="username" required>
            </div>

            <div class="useremail-input-area">
                <label>Email: </label>
                <input id="email-input" class="text-input" type="email" name="email" required>
            </div>

            <div class="submit-btn-area">
                <input type="submit" id="submit-btn" class="submit-btn" value="Enviar">
            </div>

            <script>
                const submitBtn = document.getElementById('submit-btn');
                const nameInput = document.getElementById('name-input');

                const emailInput = document.getElementById('email-input');


                function setBtnValueToDefault() {
                    
                    submitBtn.setAttribute('value', 'Enviar');
                    submitBtn.style.opacity = '1'

                }

                emailInput.addEventListener('change', setBtnValueToDefault);

                nameInput.addEventListener('change', setBtnValueToDefault);

                submitBtn.addEventListener('click', () => {

                    const nameInput = document.getElementById('name-input');

                    const emailInput = document.getElementById('email-input');

                    if (emailInput.value != "" && nameInput.value != "") {

                        submitBtn.setAttribute('value', 'Aguarde...')
                        submitBtn.style.opacity = "0.5";

                    }

                })
            </script>

            <?php if ($emailSended == true) {

                print <<<_HTML_

                <div class="email-status-container">
                    <h2>O email foi enviado com sucesso!</h2>
                </div>

                <style>
                    .email-status-container {
                        display: flex;
                        animation: email-status-animation 4s;
                        position: fixed;
                        font-family: 'Roboto', sans-serif;
                        font-size: 16px;
                        font-weight: 100;
                        top: 40px;
                        right: -100%;
                        padding: 30px;
                        background-color: #C4D7B2;
                        border-radius: 4px;
                    }
                </style>

                _HTML_;

            }

            ?>

        </form>
    </div>
</body>
</html>

 