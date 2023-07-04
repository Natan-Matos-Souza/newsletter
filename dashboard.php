<?php

session_start();

if (empty($_SESSION['admin']) || $_SESSION['admin'] != true) {

    Header("Location: login.php");


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
    <link rel="stylesheet" href="style/dashboard.css">
    <title>Dashboard</title>
</head>
<body>
    <header>
        <div class="nav-bar">
            <a href="logout.php">Logout</a>
        </div>
    </header>

    <main>
        <div id="username-container">
            <h1 id="username">Olá, <?php print $_SESSION['username'];?>!</h1>
        </div>
    </main>

    <!-------------Usar display flex--------------->
    <div class="first-group-section">
        <section>
            <div class="card-container">
                <div class="image-area">
                    <img class="vetor-image" src="assets/write-vetor.png" alt="Write">
                </div>
            

                <div class="card-title-area">
                    <span class="card-title">Escrever e-mail</span>
                </div>
            </div>
        </section>

        <section>
            <div class="card-container">
                <div class="image-area">
                    <img class="vetor-image" src="assets/historic-vetor.png" alt="Historic">
                </div>
            

                <div class="card-title-area">
                    <span class="card-title">Histórico</span>
                </div>
            </div>
        </section>
    </div>

    <div class="second-group-section">
    <section>
            <div class="card-container">
                <div class="image-area">
                    <img class="vetor-image" src="assets/user-vetor.png" alt="User">
                </div>
            

                <div class="card-title-area">
                    <span class="card-title">Listar usuários</span>
                </div>
            </div>
        </section>

        <section>
            <div class="card-container">
                <div class="image-area">
                    <img class="vetor-image" src="assets/search-vetor.png" alt="Search">
                </div>
            

                <div class="card-title-area">
                    <span class="card-title">Pesquisar</span>
                </div>
            </div>
        </section>
    </div>

</body>
</html>