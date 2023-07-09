<?php

session_start();

if (empty($_SESSION['admin']) || $_SESSION['admin'] != true) {
    Header("Location: http://localhost/test/login");
    return;
}

require "database_connection.php";

if (isSet($_GET['username'])) {

    $userName = $_GET['username'];

    $dbSearch = $databaseConnection->query("SELECT * FROM assinantes WHERE username LIKE '$userName%'");

    $rowCount = $dbSearch->num_rows;

    if ($rowCount < 1) {
        print 'Nenhum usuário encontrado!';
        return;
    }

}

if (empty($_GET['username'])) {

    $dbSearch = $databaseConnection->query("SELECT * FROM assinantes");

    $rowCount = $dbSearch->num_rows;

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página</title>
</head>
<body>
    <h1>Página para listar usuários</h1>

    <?php

    if (isSet($userName)) {
        for ($i = 0; $i < $rowCount; $i++) {

            $userInfo = $dbSearch->fetch_array();

            print <<<_HTML_
            $userInfo[1]<br>
            $userInfo[2]<br>
            <hr>
            _HTML_;
        }
    }

    if (empty($_GET['username'])) {

        for ($i = 0; $i < $rowCount; $i++) {

            $userInfo = $dbSearch->fetch_array();

            print <<<_HTML_
            $userInfo[1]<br>
            $userInfo[2]<br>
            <hr>
            _HTML_;
        }

    }

    ?>
</body>
</html>