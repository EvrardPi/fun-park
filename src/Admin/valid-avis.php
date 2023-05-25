<?php
session_start();
if (!empty($_GET)) {
    require_once('Structure/Bdd/config.php'); // intègre la base de données
    $noAv = $_GET['numAvis'];
    $numAttrac = $_GET['numAttrac'];
    $yes = "Yes";
    $delAv = $bdd->prepare('UPDATE avis SET visible = ? WHERE id = ?');
    $delAv->execute(array($yes, $noAv));
    header("Location: view.php?id=" . $_SESSION['id'] . "&attrac=" . $numAttrac);
    exit(); // évite que la page chargée sur le serveur continue de s'exécuter après redirection du navigateur
}
