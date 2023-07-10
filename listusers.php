<?php

session_start();

if (!isSet($_SESSION['admin']) || $_SESSION['admin'] != true) {
    Header("Location: http://localhost/test/login");
    return;
}

require "database_connection.php";

global $databaseConnection;

if (isSet($_GET['username'])) {

    $userName = filter_input(INPUT_GET, 'username', FILTER_SANITIZE_SPECIAL_CHARS);

    $dbSearch = $databaseConnection->query("SELECT username, useremail FROM assinantes WHERE username LIKE '$userName%'");

    $rowCount = $dbSearch->num_rows;

    if ($rowCount < 1) {
        print json_encode("Não existe usuários com nome $userName", JSON_UNESCAPED_UNICODE);
        http_response_code(404);
        return;
    }

    $usersInfo = $dbSearch->fetch_all(MYSQLI_ASSOC);

    $rowCount = [
            "members" => $rowCount
    ];

    array_push($usersInfo, $rowCount);

    print json_encode($usersInfo);
    http_response_code(200);
    return;
}

?>

<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Natan Matos</title>
        <meta name="description" content="Página para listar usuários">
    </head>

    <body>
        <main>
            <form>
                <input type="text" class="search-input">
                <input type="submit" class="submit-btn">
            </form>
        </main>



        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
        <script src="js/listusers.js"></script>
    </body>
</html>
