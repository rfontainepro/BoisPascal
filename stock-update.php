<?php 
session_start();
require_once 'connect.php';
/*------------------------------------------------------------------------------------------------------*/
$id = $_GET['id'];

/* Récupération de la quantité souhaitée par le client */
$reqQuantity = $db->query("SELECT * FROM `requests` WHERE id_request = '$id'");
$quantityClient = $reqQuantity->fetch(PDO::FETCH_ASSOC);

/* Recherche du stock existant par rapport à la demande du client */
$reqStock = $db->query("SELECT articles.length, articles.stock, categories.name_category FROM articles 
INNER JOIN categories ON articles.categories = categories.id_category WHERE (name_category 
LIKE '%$quantityClient[categories]%') AND (length LIKE '%$quantityClient[length]%');");

$quantityStock = $reqStock->fetch(PDO::FETCH_ASSOC);

/*------------------------------------------------------------------------------------------------------*/
/* Soustraire stock existant par la quantité souhaitée par le client */
$quantityRest = $quantityStock['stock'] - $quantityClient['quantity'];

/* Récupération de l'ID (Accepted = 1 / En attente = 0) */
$reqOkNot = $db->query("SELECT `accepted` FROM `requests` WHERE id_request = '$id'");

$okNot = $reqOkNot->fetch(PDO::FETCH_ASSOC);
/*------------------------------------------------------------------------------------------------------*/

/* Message d'erreur si pas stock suffisant et si demande déjà acceptée, sinon redirection sur la page des requêtes si ok */
if($quantityRest >= 0 & $okNot['accepted'] === 0) {

    /* Passage 0 > 1 dans le tableau pour acceptation de la demande du client */
    $edited = $db->prepare("UPDATE `requests` SET accepted = 1 WHERE id_request = '$id'");
    $edited->execute();

    /* Mise à jour du stock existant si suffisant après acceptation de la demande du client */
    $updateStock = $db->query("UPDATE `articles` INNER JOIN categories ON articles.categories = categories.id_category 
    SET `stock` = '$quantityRest' WHERE (name_category LIKE '%$quantityClient[categories]%') AND (length LIKE '%$quantityClient[length]%');");
    $updateStock->execute();

    /* -------------------------------------------------- Envoi du mail au Client ------------------------- */

    /* Récupération de l'adresse email par rapport à l'ID */
    $reqMail = $db->query("SELECT * FROM `requests` WHERE id_request = '$id'");
    $mail = $reqMail->fetch(PDO::FETCH_ASSOC);
    /* ------------------------------------------------------------------ Personnalisation du MAIL -------- */
    $full_name = $name.' '.$firstname; /* FUSION du prénom + nom */
    $messageClient = 'Bonjour '.$firstname.' !<br><br>Votre commande [ N°' .$mail['id_request'].' ] de
     ' .$mail['categories'].' en '.$mail['length'].' cm pour '.$mail['quantity'].' stères a été validée !<br>
     <br>Nous allons bientôt livrer votre commande !<br><br>Bien cordialement,<br>_______________________<br>&#127795; Bois Pascal';
    /*------------------------------------------------------*/
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

    $to       = $mail['mail']; /* A qui */
    $headers .= 'From: Bois Pascal <noreply@boispascal.com>' . "\r\n"; /* De qui */
    $subject  = "Commande Validée !"; /* Titre du mail */

    mail($to, mb_encode_mimeheader($subject), $messageClient, $headers);

    /*------------------------------------------------------------------------------------------------------*/

    header('Location: admin.php');
    echo "Demande accepted !";

} else {
    /*------------------------------------------------------*/
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

    $to       = "support@boispascal.com"; /* A qui */
    $headers .= 'From: Support <support@boispascal.com>' . "\r\n"; /* De qui */
    $subject  = "Avertissement : Erreur DB STOCK-UPDATE sur boispascal.com"; /* Titre du mail */
    
    mail($to, mb_encode_mimeheader($subject), $messageErreur, $headers);
    /*------------------------------------------------------*/

    echo "Il y a une erreur ! (Stock insuffisant / Demande déjà acceptée)"; /* Message d'erreur pour le client */
};