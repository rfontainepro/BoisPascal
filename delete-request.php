<?php
session_start();
require_once 'connect.php';
/*------------------------------------------------------*/
$id = $_GET['id'];

/* Récupération de la quantité souhaitée par le client */
$reqQuantity = $db->query("SELECT * FROM `requests` WHERE id_request = '$id'");
$quantityClient = $reqQuantity->fetch(PDO::FETCH_ASSOC);

/* Recherche du stock existant par rapport à la demande du client */
$reqStock = $db->query("SELECT articles.length, articles.stock, categories.name_category FROM articles INNER JOIN categories ON articles.categories = categories.id_category WHERE (name_category LIKE '%$quantityClient[categories]%') AND (length LIKE '%$quantityClient[length]%');");
$quantityStock = $reqStock->fetch(PDO::FETCH_ASSOC);

/* Additionner stock existant à la quantité souhaitée par le client */
$quantityRest = $quantityStock['stock'] + $quantityClient['quantity'];

/* Récupération de l'ID (Accepted = 1 / En attente = 0) */
$reqOkNot = $db->query("SELECT `accepted` FROM `requests` WHERE id_request = '$id'");
$okNot = $reqOkNot->fetch(PDO::FETCH_ASSOC);

/*------------------------------------------------------------------------------------------------------*/
/* Diag */
var_dump($quantityClient['quantity']);
var_dump($quantityStock['stock']);
var_dump($quantityRest);
var_dump($okNot['accepted']);

/*------------------------------------------------------------------------------------------------------*/
/* Si suppression d'une demande déjà acceptée, le bois retiré du stock sera recrédité */
if($okNot['accepted'] === 1) {
    /* Mise à jour du stock existant si demande acceptée est refusée */
    $updateStock = $db->query("UPDATE `articles` INNER JOIN categories ON articles.categories = categories.id_category SET `stock` = '$quantityRest' WHERE (name_category LIKE '%$quantityClient[categories]%') AND (length LIKE '%$quantityClient[length]%');");
    $updateStock->execute();

    /* Suppression de la demande du client */
    $del = $db->query("DELETE FROM `requests` WHERE `id_request` = '$id'");

    header('Location: request.php');
    echo "Demande supprimée et bois recrédité !";
};

/*------------------------------------------------------------------------------------------------------*/
/* Si suppresion d'une demande en attente, suppresion directe */
if($okNot['accepted'] === 0) {
    /* Suppression de la demande du client */
    $del = $db->query("DELETE FROM `requests` WHERE `id_request` = '$id'");

    header('Location: request.php');
    echo "Demande supprimée !";
};