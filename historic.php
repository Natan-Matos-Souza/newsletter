<?php

session_start();

if (!isSet($_SESSION['admin']) || $_SESSION['admin'] != true) {
    Header("Location: http://localhost/test/login");
}

require "database_connection.php";

global $databaseConnection;

if (isSet($_GET['get_historic']) && !empty($_GET['get_historic'])) {

    $dbSearch = $databaseConnection->query("SELECT * FROM emailhistory");

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


?>

<html>
    <head>
        <meta charset="UTF-8">
        <meta name="description" content="Histórico de e-mails">
        <tite>Histórico de e-mails</tite>
    </head>

    <body>
        <main>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
            <script src="js/historic.js "></script>
        </main>
    </body>
</html>
