<?php
require_once 'connect.php';
/*------------------------------------------------------*/
$login = $_POST['login'];
$email = $_POST['email'];
$password = $_POST['password'];
$hidden = password_hash($password, PASSWORD_BCRYPT);
/*------------------------------------------------------*/
if(isset($_POST['submit'])) {
    $user = $db->query("INSERT INTO `admin` (`login`, `email`, `password`) VALUES ('$login', '$email', '$hidden')");
}

/*------------------------------------------------------*/
if($user){
    header('Location: request-admin.php');

    /*------------------------------------------------------*/
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

    $to       = "support@boispascal.com"; /* A qui */
    $headers .= 'From: Support <support@boispascal.com>' . "\r\n"; /* De qui */
    $subject  = "Avertissement : Nouveau USER sur boispascal.com"; /* Titre du mail */

    mail($to, mb_encode_mimeheader($subject), mb_encode_mimeheader("Nouveau compte ! ($login)"), $headers);

} else {

    /*------------------------------------------------------*/
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

    $to       = "support@boispascal.com"; /* A qui */
    $headers .= 'From: Support <support@boispascal.com>' . "\r\n"; /* De qui */
    $subject  = "Avertissement : Erreur DB-NEW-USER sur boispascal.com"; /* Titre du mail */

    mail($to, mb_encode_mimeheader($subject), mb_encode_mimeheader($messageErreur), $headers);
    /*------------------------------------------------------*/

    echo "Oops, il y a eu une erreur !"; /* Message d'erreur pour le client */
};