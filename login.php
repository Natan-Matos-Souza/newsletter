<?php

require "database_connection.php";

global $databaseConnection;

if (isSet($_POST['username']) && isSet($_POST['password'])) {

    $userName = htmlspecialchars(trim($_POST['username']));

    $password = htmlspecialchars(trim($_POST['password']));

    
    function sanitizeInput($string) {

        $dirtyCharacters = array('\\', ';', '-', ',', '.', '<', '>', '=');

        $replaceFor = array('', '', '', '', '', '', '', '');

        $sanitizedString = str_replace($dirtyCharacters, $replaceFor, $string);

        return $sanitizedString;

    }

    $userName = sanitizeInput($userName);

    $dbSearch = $databaseConnection->query("SELECT * FROM admin_users WHERE username='$userName'");

    $searchResult = $dbSearch->fetch_array();

    if (password_verify($password, $searchResult[1])) {

        session_start();

        $_SESSION['admin'] = true;
        $_SESSION['username'] = $searchResult[0];

        Header("Location: http://localhost/test/dashboard");

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
    <title>Login</title>
</head>
<body>

    <main>
        <div id="logo">
            <div id="logo-area">
                <a href="index.php">
                    <img src="assets/logo-dark-mode.png" alt="dark-logo" id="dark-logo">
                </a>
            </div>
        </div>

        <h2 id="page-title">Login</h2>

        <div class="container">
            <form method="POST">
                <div class="text-area">
                    <h2 id="text">Digite o seu nome:</h2>
                </div>


                <!-----------Primeiro input------------->
                <div class="input-area" id="first-input-div">
                    <input type="text" class="input" id="first-input" name="username">
                </div>

                <!--------Segundo Input----------------->
                <div class="input-area" id="second-input-div">
                    <input type="password" class="input" id="second-input" name="password">
                </div>

                <!------Botão para alterar input------->
                <div class="btn-area">
                    <input type="button" value="Próximo" class="submit-btn" id="change-input-btn">
                </div>

                <div class="btn-area">
                    <input type="submit" value="Enviar" class="submit-btn" id="submit-btn">
                </div>


            </form>
        </div>

        <script src="js/login.js"></script>


    </main>
        
        <footer id="footer">
                <span id="footer-text">Estudante News | Por: Natan Matos</span>
        </footer>

</body>
</html>