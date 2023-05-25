<?php
session_start();

if (!empty($_GET['statut1784'])) {
    require_once "Structure/Bdd/config.php";
    $delElement = $_GET['statut1784'];
    $delE = $bdd->prepare('DELETE FROM statut_attractions WHERE statut = ?');
    $delE->execute([$delElement]);
    header("Location: attractions.php?id=" . $_SESSION['id']);
    exit();
}

if (!empty($_GET['agemin1784'])) {
    require_once "Structure/Bdd/config.php";
    $delElement = $_GET['agemin1784'];
    $delE = $bdd->prepare('DELETE FROM age_min_attractions WHERE age = ?');
    $delE->execute([$delElement]);
    header("Location: attractions.php?id=" . $_SESSION['id']);
    exit();
}

if (!empty($_GET['type1784'])) {
    require_once "Structure/Bdd/config.php";
    $delElement = $_GET['type1784'];
    $delE = $bdd->prepare('DELETE FROM type_attractions WHERE type = ?');
    $delE->execute([$delElement]);
    header("Location: attractions.php?id=" . $_SESSION['id']);
    exit(); // évite que la page chargée sur le serveur continue de s'exécuter après redirection du navigateur
}

if (!empty($_GET['id1784'])) {
    require_once "Structure/Bdd/config.php";
    $delElement = $_GET['id1784'];
    $delE = $bdd->prepare('DELETE FROM attractions WHERE id = ?');
    $delE->execute([$delElement]);
    header("Location: attractions.php?id=" . $_SESSION['id']);
    exit();
}

if (!empty($_GET['ticket1784'])) {
    require_once "Structure/Bdd/config.php";
    $delElement = $_GET['ticket1784'];
    $delE = $bdd->prepare('DELETE FROM ticket WHERE id = ?');
    $delE->execute([$delElement]);
    header("Location: ticket.php?id=" . $_SESSION['id']);
    exit();
}
