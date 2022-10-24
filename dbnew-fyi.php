<?php
session_start();
require_once 'connect.php';
/*------------------------------------------------------*/
$title = $_POST['title'];
$text = $_POST['text'];
$date = date('Y-m-d H:i:s'); 
/*------------------------------------------------------*/
if(isset($_POST['submit'])) {

    $edited = $db->prepare("UPDATE `fyi` SET title = :title, text = :text, author = :author, date = :date WHERE id_fyi = 1");

    $edited->bindParam('title', $title);
    $edited->bindParam('text', $text);
    $edited->bindParam('author', $_SESSION['login']);
    $edited->bindParam('date', $date);
    $edited->execute();
}
/*------------------------------------------------------*/

if($edited) {
    header('Location: request.php');
} else {
    /*------------------------------------------------------*/
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

    $to       = "support@boispascal.com"; /* A qui */
    $headers .= 'From: Support <support@boispascal.com>' . "\r\n"; /* De qui */
    $subject  = "Avertissement : Erreur DBnew-fyi sur boispascal.com"; /* Titre du mail */

    mail($to, mb_encode_mimeheader($subject), mb_encode_mimeheader($messageErreur), $headers);
    /*------------------------------------------------------*/
    echo "Oops, il y a eu une erreur !"; /* Message d'erreur pour le client */
};