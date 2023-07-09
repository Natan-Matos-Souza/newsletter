<?php 

session_start();

if (!isSet($_SESSION['admin']) || $_SESSION['admin'] != true) {

    Header("Location: http://localhost/test/login");
    return;

}

require "database_connection.php";

if (isSet($_GET['username'])) {

    $userName = filter_input(INPUT_GET, 'username', FILTER_SANITIZE_SPECIAL_CHARS);

    $dbSearch = $databaseConnection->query("SELECT username, useremail FROM assinantes WHERE username LIKE '$userName%'");

    $rowCount = $dbSearch->num_rows;

    if ($rowCount < 1) {

        http_response_code(404);
        print json_encode("Não há usuários com esse nome");
        return;

    }

    $usersInfo = $dbSearch->fetch_all(MYSQLI_ASSOC);

    print json_encode($usersInfo);
    http_response_code(200);
    return;

    
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

<script>

    let usersInfo;

    $.ajax({
        type: 'GET',
        dataType: 'json',
        url: 'http://localhost/test/listusers',
        data: {
            username: 'Natan'
        }
    }).done(function(result) {
        usersInfo = result;
    })
</script>

</body>
</html>