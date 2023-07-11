<?php

session_start();

if (!isSet($_SESSION['admin']) || $_SESSION['admin'] != true) {
    Header("Location: http://localhost/test/login");
}

require "database_connection.php";

global $databaseConnection;

if (isSet($_GET['get_historic']) && empty($_GET['get_historic'])) {

    $dbSearch = $databaseConnection->query("SELECT * FROM emailhistory ORDER BY id DESC");

    $rowCount = $dbSearch->num_rows;

    if ($rowCount < 1) {
        print json_encode("Nenhum e-mail foi enviado", JSON_UNESCAPED_UNICODE);
        http_response_code(404);
        return;
    }

    $emailHistory = $dbSearch->fetch_all(MYSQLI_ASSOC);

    print json_encode($emailHistory);
    return;


}

if (isSet($_GET['get_historic']) && !empty($_GET['get_historic'])) {

    $emailName = filter_input(INPUT_GET, 'get_historic', FILTER_SANITIZE_SPECIAL_CHARS);

    $dbSearch = $databaseConnection->query("SELECT * FROM emailhistory WHERE email_title, email_content='$emailName'");

    $rowCount = $dbSearch->num_rows;

    if ($rowCount < 1) {
        print json_encode("Não exite e-mails com esse nome", JSON_UNESCAPED_UNICODE);
        http_response_code(404);
        return;
    }

    $emailFound = $dbSearch->fetch_all(MYSQLI_ASSOC);
    http_response_code(200);
    return;


}


?>

<html>
    <head>
        <meta charset="UTF-8">
        <meta name="description" content="Histórico de e-mails">
        <link rel="stylesheet" href="style/historic.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,500;0,700;1,100;1,300;1,400;1,500&display=swap" rel="stylesheet">
        <title>Histórico de e-mails</title>
    </head>

    <body>
        <main>

            <div class="logo-area">
                <img src="assets/logo-dark-mode.png" alt="Logo" class="logo-img">
            </div>

            <h2 class="historic-title">Aguarde...</h2>

            <div class="email-container">

            </div>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
            <script src="js/historic.js "></script>
        </main>
    </body>
</html>
