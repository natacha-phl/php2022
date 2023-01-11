<?php

if ($_SESSION['membre_connecté'] !== true){
    header("Location: connexion.php");
    exit;
};

?>