<?php

require "database_connection.php";

if (isSet($_POST['username']) && isSet($_POST['username'])) {

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

        Header("Location: dashboard.php");

    }


}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <form method="POST">
        <label>Usuário: </label>
        <input type="text" name="username"><br>
        <label>Password: </label>
        <input type="password" name="password">
        <input type="submit" value="Envar">
    </form>
</body>
</html>