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

if (isSet($_POST['email_title']) && isSet($_POST['email_content'])) {

    $emailTitle = $_POST['email_title'];

    $emailContent = $_POST['email_content'];

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
                    <input type="submit" class="submit-btn" value="Salvar">
                </div>
            </form>
        </div>
    </main>


    
    <section>
        <div class="post-area">
            <div class="post-area-title">
                <h2 class="post-title">Exemplar do e-mail</h2>
            </div>

            <div class="email-test" id="email-test">
                <div class="email-title-area">
                    <h2 class="email-title"></h2>
                </div>
                <hr>
                <div class="email-content-area">
                    <div class="email-content"></div>
                </div>
            </div>

            <div class="confirm-area">
                <div class="confirm-area-title">
                    <span class="confirm-area-span">Você deseja enviar o email?</span>
                </div>

                <div class="confirm-area-btn">
                    <input class="confirm-btn" type="button" value="Enviar">
                    <input class="deny-btn" type="button" value="Cancelar">
                </div>
            </div>
        </div>
    </section>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

    <script src="js/sendemail.js"></script>

    <script>


        $('.confirm-btn').on('click', function() {


            function emailPosted()
            {
                const emailPostArea = document.querySelector('.post-area');
                const emailForm = document.querySelector('form');

                const emailFormCoordenates = emailForm.scrollTop;

                scrollTo(0, emailFormCoordenates);

                document.addEventListener('scroll', () => {

                    if (window.pageYOffset == emailFormCoordenates)
                    {
                        emailPostArea.style.display = 'none';
                    }


                })

            }

            function getEmailData()
            {
                const firstInputData = document.querySelector('.first-input').value;
                const secondInputData = document.querySelector('.second-input').value;

                const emailData = {
                    email_title: firstInputData,
                    email_content: secondInputData
                };

                return emailData;
            }

            const emailData = getEmailData();

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: 'http://localhost/test/sendemail.php',
                data: {
                    email_title: emailData.email_title,
                    email_content: emailData.email_content
                },
                sucess: emailPosted()
            })

        })
    </script>


    
    
</body>
</html>