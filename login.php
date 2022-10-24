<?php
session_start();
require_once 'connect.php';
/*------------------------------------------------------*/
if(isset($_POST['login']) && isset ($_POST['password'])) {

    $req_user = "SELECT * FROM `admin` WHERE login = :login OR email = :email";

    $resultat = $db->prepare($req_user);
    $login = $_POST['login'];
    $password = $_POST['password'];
    $hashPassword = password_hash($password, PASSWORD_BCRYPT);
    $resultat->execute(array("login" => $login, "email" => $login));
    $result = $resultat->fetchAll(PDO::FETCH_ASSOC);

    if ($resultat->rowcount() == 1) {
        if (password_verify($_POST['password'],$result[0]['password'])){
            $_SESSION['login'] = $login;
            $_SESSION['password'] = $hashPassword;
            $authOK = true; 
        } else {

            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
            $headers .= 'From: Support <support@boispascal.com>' . "\r\n"; /* De qui */

            $to       = "support@boispascal.com"; /* A qui */
            $subject  = "Avertissement : Erreur LOGIN sur boispascal.com"; /* Titre du mail */

            mail($to, mb_encode_mimeheader($subject), mb_encode_mimeheader("L'utilisateur $login n'a pas pu se connecter !"), $headers);
            /*------------------------------------------------------*/

            echo "<h3 style='text-align:center; margin-top:20px;'>Nom Utilisateur ou Email incorrect !</h3>";
            /* echo "<h3 style='text-align:center; margin-top:20px;'>Mot de passe perdu ?</h3>"; */
        }

    } else {

        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= 'From: Support <support@boispascal.com>' . "\r\n"; /* De qui */

        $to       = "support@boispascal.com"; /* A qui */
        $subject  = "Avertissement : Erreur LOGIN sur boispascal.com"; /* Titre du mail */

        mail($to, mb_encode_mimeheader($subject), mb_encode_mimeheader("L'utilisateur $login n'a pas pu se connecter !"), $headers);
        /*------------------------------------------------------*/

        echo "<h3 style='text-align:center; margin-top:20px;'>Nom Utilisateur ou Email incorrect !</h3>";
        /* echo "<h3 style='text-align:center; margin-top:20px;'>Mot de passe perdu ?</h3>"; */
    };
};
/*--------------------------------------------------------------------------------------------------------------------*/
ob_start();
?> 
<!--------------------------------------------------------------------------------------------------------------------->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amatic+SC:wght@700&family=Josefin+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!------------------------------------>
    <link rel="icon" type="image/png" href="img/favicon/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="img/favicon/favicon-16x16.png" sizes="16x16" />
    <!------------------------------------>
    <link rel="stylesheet" href="main.css">
    <title>BOIS PASCAL - Se connecter</title>
</head>
<body>
    <section id="login">
        <div class="container dflex">
<!--------------------------------------------------------------------------------------------------------------------->
<?php
if(isset($_SESSION['login']) && ($result[0]['rule']) === 1) {
    header('Location: request-admin.php');
} else if (isset($_SESSION['login'])) {
    header('Location: request.php');
} else {
?>
<!--------------------------------------------------------------------------------------------------------------------->
            <form action="#" method="POST">
                <div class="form-wrapper">
                    <div>
                        <label for="login">Nom Utilisateur / Email &#8595;</label>
                        <input type="text" name="login" id="login">
                    </div>
                    <div>
                        <label for="password">Mot de passe &#8595;</label>
                        <input type="password" name="password" id="password">
                    </div>
                </div>
                <input type="submit" name="submit" value="Se connecter" class="add-input">
            </form>
<!--------------------------------------------------------------------------------------------------------------------->
<?php
}
?>
<!--------------------------------------------------------------------------------------------------------------------->
        </div>
    </section>
</body>
</html>