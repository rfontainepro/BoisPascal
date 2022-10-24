<?php

/* Cacher les messages d'erreurs PHP */
ini_set('display_errors', 'off');
/*---------------------------------------------------------------------------------------------------------*/
require_once 'connect.php'; // Fichier contenant les paramêtres pour se connecter à la DB

/* ---------------------------- SEARCH --------------------------------------------------- */
$searchMail = $_GET["search-mail"];
$searchNumero = $_GET["search-numero"];

/* Recherche basée sur le mail et le numéro de commande */
$reqSearch = $db->query("SELECT accepted FROM requests WHERE `mail` LIKE '$searchMail' AND `id_request` LIKE '$searchNumero';");
$repSearch = $reqSearch->fetch(PDO::FETCH_ASSOC);

/* Rechercher la date de la dernière commande */
$reqLastUpdate = $db->query("SELECT date FROM `requests` ORDER BY `requests`.`date` DESC LIMIT 1;");
$repLastUpdate = $reqLastUpdate->fetch(PDO::FETCH_ASSOC);
?> 
<!---------------------------------------------------------------------------------------------------------------------------------------------------------->
<div class="container">
    <form id="search-follow-request" method="GET" class="dflex fdcolumn">
        <div id="cards-shadow" class="dflex">
            <div class="form-wrapper">
                <div class="dflex fdcolumn">
                    <label for="mail">Email &#8595;</label>
                    <input required type="text" name="search-mail" id="mail" placeholder="Email" maxlength=60 
                    pattern="[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([_\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})">
                </div>
                <p>Mise à jour de la base de données : <?php echo $repLastUpdate['date'] ?></p>
            </div>                                
            <div class="form-wrapper">
                <div class="dflex fdcolumn">
                    <label for="numero">Commande N° &#8595;</label>
                    <input required type="text" name="search-numero" id="numero" placeholder="Numéro de commande" maxlength=3 pattern="^[0-9]*$">
                </div>
                <p>
<!---------------------------------------------------------------------------------------------------------------------------------------------------------->
<?php
/* Résultat de la recherche */
if($repSearch['accepted'] === 1) {
    echo 'Votre demande a été acceptée !';
} if($repSearch['accepted'] === 0) {
    echo 'Votre demande est en attente !';
}
?>
<!---------------------------------------------------------------------------------------------------------------------------------------------------------->
                </p>
            </div>
        </div>
        <input id="invisible" type="submit" />
    </form>
</div>