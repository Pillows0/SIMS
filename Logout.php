<?php

    session_start();

    //destroy/end session
    session_destroy(); 

    header('Location: Login.php'); //redirect to login

?>