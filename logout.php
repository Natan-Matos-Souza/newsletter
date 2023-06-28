<?php

session_start();

if (isSet($_SESSION['username']) && isSet($_SESSION['admin'])) {

    print <<<_HTML_

    <div class="container">
        <h2>Até mais, $_SESSION[username]!</h2>
        <p>Sentiremos a sua falta!</p>
    </div>

    _HTML_;
}


session_destroy();

Header("Location: login.php");

?>
