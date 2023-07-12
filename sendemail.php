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

    Header('Content-Type: application/json');

    $emailTitle = $_POST['email_title'];

    $emailContent = $_POST['email_content'];

    $storeData = $databaseConnection->query("INSERT INTO emailhistory(email_title, email_content, sent) VALUES ('$emailTitle', '$emailContent', false)");


    //Envia email para todos usuários.

    
    $dbSearch = $databaseConnection->query("SELECT * FROM assinantes");
    $membersToSend = $dbSearch->num_rows;

    for ($i = 0; $i < $membersToSend; $i++) {

        $userInfo = $dbSearch->fetch_array(MYSQLI_ASSOC);
        $sentData = $i + 1;

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
            $mail->addAddress($userInfo['useremail']);

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

    print json_encode([
        "email_sent" => $sentData,
        "members" => $membersToSend
    ]);

    http_response_code(201);
    $storeData = $databaseConnection->query("UPDATE emailhistory SET sent=true WHERE email_title='$emailTitle'");

    return;
    
}

if (isSet($_GET['get_users_number'])) {

    $dbSearch = $databaseConnection->query("SELECT * FROM assinantes");

    $newsletterMembers = $dbSearch->num_rows;

    print json_encode([
        "newsletter_members" => $newsletterMembers
    ]);

    return;

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

    <div class="modal-area">
        <div class="modal">
            <div class="modal-title-area">
                <h2 class="modal-title">O email está sendo enviado!</h2>
            </div>

            <div class="email-status-area">
                <span class="modal-status"></span>
            </div>

            <div class="modal-image">
                <img src="https://i.gifer.com/origin/34/34338d26023e5515f6cc8969aa027bca_w200.gif" alt="" class="modal-img">
            </div>

            <div class="close-modal-btn-area">
                <input type="button" class="close-modal-btn" value="Fechar">
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

    <script src="js/sendemail.js"></script>

    <script>

        let newsletterMembers;

        //Pega a quantidade de membros cadastrados na newsletter. Esse dado é proveniente do back-end.
       
        function ajaxRequest()
        {
            $.ajax({
                method: 'GET',
                url: 'http://localhost/test/sendemail.php',
                dataType: 'json',   
                data: 'get_users_number'
            }).done(function(result) {
                newsletterMembers = result.newsletter_members;

                console.log(newsletterMembers);
            })

            setTimeout(() => {
                ajaxRequest();
            }, 300 * 1000);
        }

        ajaxRequest();

        function sendingFeedback()
        {
            const modalTitle = document.querySelector('.modal-title');
            const modalContainer = document.querySelector('.modal-area');
            const modalImage = document.querySelector('.modal-image');
            const modalStatus = document.querySelector('.modal-status');
            const modalCloseBtn = document.querySelector('.close-modal-btn');

            modalCloseBtn.style.display = 'none';

            modalTitle.innerHTML = 'O e-mail está sendo enviado!'
            modalImage.style.display = 'block';
            modalContainer.style.display = 'block';
            modalStatus.innerHTML = `Estamos enviando o seu e-mail para ${newsletterMembers} usuários! Isso pode demorar alguns minutos!`
        }

        function completedRequest(result)
        {
            const modalContainer = document.querySelector('.modal-area');
            const modalTitle = document.querySelector('.modal-title');
            const modalStatus = document.querySelector('.modal-status');
            const modalImage = document.querySelector('.modal-image');
            const modalCloseBtn = document.querySelector('.close-modal-btn');

            modalImage.style.display = 'none';
            modalTitle.innerHTML = 'E-mail enviado com sucesso!';
            modalStatus.innerHTML = `O seu e-mail foi enviado com sucesso para ${result.email_sent} usuários!`

            modalCloseBtn.style.display = 'inline';

            modalCloseBtn.addEventListener('click', () => {
                modalContainer.style.display = 'none';

                const emailTestContainer = document.querySelector('.post-area');
                const emailFormCoordenates = document.querySelector('form').scrollTop;

                document.querySelector('.first-input').value = '';
                document.querySelector('.second-input').value = '';
                scrollTo(0, emailFormCoordenates);

                document.addEventListener('scroll', () => {

                    if (window.pageYOffset == emailFormCoordenates)
                    {
                        emailTestContainer.style.display = 'none';
                    }

                })


            })

        }

        function getData()
        {
            const emailTitleData = document.querySelector('.first-input').value;
            const emailContentData = document.querySelector('.second-input').value;

            const emailData = {
                email_title: emailTitleData,
                email_content: emailContentData
            };

            return emailData;

        }

        //Função para enviar dados.
        $('.confirm-btn').on('click', function() {

            const emailData = getData();

            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: 'http://localhost/test/sendemail.php',
                data: {
                    email_title: emailData.email_title,
                    email_content: emailData.email_content,
                },
                beforeSend: sendingFeedback()
                }).done(function(result) {
                    completedRequest(result);
                }).fail(function() {
                    console.log('Ocorreu um erro!');
                })
        })

    </script>


    
    
</body>
</html>