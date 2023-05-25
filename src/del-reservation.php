<?php
session_start();
if (!empty($_GET)) {
    require_once('Structure/Bdd/config.php'); // intègre la base de données
    $noRes = $_GET['numRes'];
    $delRes = $bdd->prepare('DELETE FROM reservation WHERE id = ?');
    $delRes->execute([$noRes]);
    header("Location: myfunpark.php?id=" . $_SESSION['id']);
    exit(); // évite que la page chargée sur le serveur continue de s'exécuter après redirection du navigateur
}
?>