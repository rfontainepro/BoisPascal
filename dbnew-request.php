<?php
session_start();
require_once 'connect.php';
/*------------------------------------------------------*/
/* Récupération des inputs et les stocker dans des variables */
$firstname = $_POST['firstname'];
$name = $_POST['name'];
$phone = $_POST['phone'];
$mail = $_POST['mail'];
$adress = $_POST['adress'];
$length = $_POST['length'];
$quantity = $_POST['quantity'];
$categories = $_POST['categories'];
$date = date('Y-m-d H:i:s'); 
/*------------------------------------------------------*/
/* Création de la requête dans le tableau */
if(isset($_POST['submit'])) {

    $new = $db->prepare("INSERT INTO requests (firstname, name, phone, mail, adress, length, quantity, categories, date) VALUES (:firstname, :name, :phone, :mail, :adress, :length, :quantity, :categories, :date)");
    
    $new->bindParam('firstname', $firstname);
    $new->bindParam('name', $name);
    $new->bindParam('phone', $phone);
    $new->bindParam('mail', $mail);
    $new->bindParam('adress', $adress);
    $new->bindParam('length', $length);
    $new->bindParam('quantity', $quantity);
    $new->bindParam('categories', $categories);
    $new->bindParam('date', $date);
    $new->execute();

    /*-------------------------- Pour l'envoi de 3 mails (Pascal + Support + Client) ---------------------------*/
    /*----------------------------------------------------------------------------------------------------------*/

    if($new) {
        /* Recherche basée sur le prénom, le nom et l'email pour obtenir le numéro de la commande */
        $reqLastID = $db->query("SELECT id_request FROM requests WHERE `firstname` LIKE '$firstname' AND `name` LIKE '$name' AND `mail` LIKE '$mail';");
        $repLastID = $reqLastID->fetch(PDO::FETCH_ASSOC);

        /* ------------------------------------------------------------------ Personnalisation du MAIL -------- */
        $full_name = $name.' '.$firstname; /* FUSION du prénom + nom */
        $messagePascal = $full_name.' ( '.$mail.' ) a passé une nouvelle commande de ' .$_POST['categories'].' en '.$_POST['length'].' cm pour '.$_POST['quantity'].' stères !<br>_________________________________<br>Pour se connecter: boispascal.com/login'; /* Message personnalié adressé au gérant */
        $messageClient = 'Bonjour '.$firstname.' !<br><br>Nous avons bien reçu votre commande [ N°' .$repLastID['id_request'].' ] de ' .$_POST['categories'].' en '.$_POST['length'].' cm pour '.$_POST['quantity'].' stères !<br><br>Nous reviendrons vers vous dès que possible !<br><br>Bien cordialement,<br>_______________________<br>&#127795; Bois Pascal'; /* Message personnalié adressé au client */

        /* ----------------------------------------------- Envoi du mail à Pascal + Support -------------------- */
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

        $to       = "info@boispascal.com"; /* A qui */
        $headers .= 'From: Bois Pascal <noreply@boispascal.com>' . "\r\n"; /* De qui */
        $headers .= 'Cc: Support <support@boispascal.com>' . "\r\n"; /* Support = Moi ;-) */
        $subject  = "Nouvelle Commande !"; /* Titre du mail */

        
        mail($to, mb_encode_mimeheader($subject), $messagePascal, $headers);
        /* --------------------------------------------------- Envoi du mail au Client ------------------------- */
		if ($to) {
            /*------------------------------------------------------*/
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

            $headers .= 'From: Bois Pascal <noreply@boispascal.com>' . "\r\n"; /* De qui */
            $subject  = "Commande Reçue !"; /* Titre du mail */

            mail($mail, mb_encode_mimeheader($subject), $messageClient, $headers);
        /* ---------------------------------------------- Redirection sur index.php si tout OK ----------------- */
            header('Location: index.php');
        }
    }

} else {
    /*------------------------------------------------------*/
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

    $to       = "support@boispascal.com"; /* A qui */
    $headers .= 'From: Support <support@boispascal.com>' . "\r\n"; /* De qui */
    $subject  = "Avertissement : Erreur DB NEW-REQUEST sur boispascal.com"; /* Titre du mail */
    
    mail($to, $subject, mb_encode_mimeheader($messageErreur), $headers);
    /*------------------------------------------------------*/

    echo "Oops, il y a eu une erreur !"; /* Message d'erreur pour le client */
};