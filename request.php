<?php

/* Cacher les messages d'erreurs PHP */
ini_set('display_errors', 'off');

/*---------------------------------------------------------------------------------------------------------*/
session_start(); 
/*---------------------------------------------------------------------------------------------------------*/
require_once 'connect.php'; // Fichier contenant les paramêtres pour se connecter à la DB
require_once 'apikey.php'; // Fichier contenant la clef de l'API Météo
/*--------------------------------- Catégories En attentes / Acceptées ------------------------------------*/
if(isset($_GET['accepted-request'])) {
    $blog = $db->query('SELECT * FROM `requests` WHERE accepted = 1 ORDER BY `id_request` DESC LIMIT 9');
} else {
    $blog = $db->query('SELECT * FROM `requests` WHERE accepted = 0 ORDER BY `id_request` DESC LIMIT 9');
}
/*---------------------------------------------------------------------------------------------------------*/
$oldValueFYI = $db->query("SELECT * FROM fyi WHERE id_fyi = 1");
$FYI = $oldValueFYI->fetch(PDO::FETCH_ASSOC);
/*---------------------------------------------------------------------------------------------------------*/
// Afficher le nombre total de demandes dans la DB (acceptée)
$all = $db->prepare("SELECT COUNT(*) FROM requests WHERE accepted = 1;");
$all->execute();
$resultYes = $all->fetch();

// Afficher le nombre total de demandes dans la DB (non traitées)
$all = $db->prepare("SELECT COUNT(*) FROM requests WHERE accepted = 0;");
$all->execute();
$resultNo = $all->fetch();

// Afficher le nombre total de demandes dans la DB
$countRequest = $resultNo['0'] + $resultYes['0'];

/* ---------------------------- SEARCH --------------------------------------------------- */
// Récupération de l'input de la barre de recherche pour la stocker dans la variable $search
$search = $_GET["search"];

/* Recherche basée sur le prénom ou le nom */
$reqSearch = $db->query("SELECT * FROM requests WHERE `firstname` LIKE '$search' OR `name` LIKE '$search';");
$repSearch = $reqSearch->fetchAll(PDO::FETCH_ASSOC);

/*----------------------------------- API Météo -------------------------------------------*/

$location = 'Viry, Rhône-Alpes' ; /* Lieu pour obtenir la météo */;
$queryString = http_build_query([
  'access_key' => $APIKEY,
  'query' => $location,
]);

$ch = curl_init(sprintf('%s?%s', 'http://api.weatherstack.com/current', $queryString));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$json = curl_exec($ch);
curl_close($ch);

$api_result = json_decode($json, true);
/* --------------------------------------------------------------------------------------------------------------- */
ob_start();
/* --------------------------------------------------------------------------------------------------------------- */

if(isset($_SESSION['login'])) {
?> 
<!---------------------------------------------------------------------------------------------------------------------------------------------------------->
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
    <title>BOIS PASCAL - Administration</title>
</head>
<!---------------------------------------------------------------------------------------------------------------------------------------------------------->
<body>
    <main>
        <section id="admin-main">
            <div class="container">
<!---------------------------------------------------------------------------------------------------------------------------------------------------------->
<?php

$imgWeather = $api_result['current']['weather_icons'][0];

echo "<h3 style='margin-top:30px; padding-left: 10px; background-color: #00000021'><span>&#128197; " . date("D d M") . " - &#128338; " . date("H:i") . " - </span>[&#127777; {$api_result['current']['temperature']}℃ - &#127783; {$api_result['current']['precip']}mn ] - " . $_SESSION['login'] . "</h3>";
/* var_dump($imgWeather); /* URL de la météo actuelle avec une icône */
?>
<!---------------------------------------------------------------------------------------------------------------------------------------------------------->
            <!-- SEARCH -------------------------------->
                <form id="search-request" method="GET" >
                    <input maxlength=20 type="text" name="search" value="<?php echo $search ?>" placeholder="Rechercher parmis <?php echo($countRequest); ?> demande(s)" />
                    <input id="invisible" type="submit" />
                </form>
            <!----------------------------------------->
                <section id="add-new-account">
                    <div class="container">
                        <form action="dbnew-fyi.php" method="POST" class="dflex fdcolumn" enctype="multipart/form-data">
                            <div id="cards-shadow" class="dflex">
                                <div class="form-wrapper">
                                    <div class="dflex fdcolumn">
                                        <p>
<!---------------------------------------------------------------------------------------------------------------------------------------------------------->
<?php
if(isset($_POST['submit'])) {
    echo 'Mise à jour effectuée !';
}
?>
<!---------------------------------------------------------------------------------------------------------------------------------------------------------->
                                        </p>
                                        <label for="title">Modifier le titre &#8595;</label>
                                        <input type="text" name="title" id="title" maxlength=60 value="<?= $FYI['title']?>">
                                    </div>
                                    <p>Dernière édition le : <?php echo $FYI['date'] ?></p>
                                </div>                                
                                <div class="form-wrapper">
                                    <div class="dflex fdcolumn">
                                        <label for="text">Modifier le texte &#8595;</label>
                                        <input type="text" name="text" id="text" maxlength=70 value="<?= $FYI['text']?>">
                                    </div>
                                    <p>Dernière édition le : <?php echo $FYI['date'] ?></p>
                                </div>
                            </div>
                            <input id="invisible" type="submit" name="submit" />
                        </form>
                    </div>
                </section>
                <!----------------------------------------->
                <nav class="navigation_cat dflex">
                    <ul>
                        <li>
                            &#128270;
                        </li>
                        <li>
                            <a href="request.php?new-request" <?= !isset($_GET['accepted-request']) ? 'class="category-active"' : '' ?>>Nouvelle commande (<?=$resultNo['0']?>)</a>
                        </li>
                        <li>
                            <a href="request.php?accepted-request" <?= isset($_GET['accepted-request']) ? 'class="category-active"' : '' ?>>Commande acceptée (<?=$resultYes['0']?>)</a>
                        </li>
                    </ul>
                    <div class="logout">
                        <a href="admin.php" class="dflex">
                            <img src="img/logout.svg" alt="Log out">
                            <p class="p-id">Gérer les articles</p>
                        </a>
                    </div>
                    <div class="logout">
                        <a href="logout.php" class="dflex">
                            <img src="img/logout.svg" alt="Log out">
                            <p class="p-id">Se déconnecter</p>
                        </a>
                    </div>
                </nav>
<!---------------------------------------------------------------------------------------------------------------------------------------------------------->
<?php
    if(count($repSearch) == 0) {
        echo '<div class="card-ordered dflex">';
        while ($newRequest = $blog->fetch(PDO::FETCH_ASSOC)) {
?>
<!---------------------------------------------------------------------------------------------------------------------------------------------------------->
                <div class="article-card dflex fdcolumn">
                    <div class="article-card-text">
                        <h3><?=$newRequest['firstname']?> <?=$newRequest['name']?></h3>
                        <p class="p-id">
                            Commande N° <?=$newRequest['id_request']?><br>
                            &#128197; <?=$newRequest['date']?>  
                        </p>
                        <p class="informations-one">
                            &#128222; <?=$newRequest['phone']?><br>
                            <a href="mailto:<?=$newRequest['mail']?>" target="_blank">&#128232; <?=$newRequest['mail']?></a><br>
                        </p>
                        <div id="adress">
                            <div id="part-one">
                                &#127968;
                            </div>
                            <div id="part-two">    
                                <a href="https://www.google.fr/maps/place/<?=$newRequest['adress']?>" target="_blank"><?=$newRequest['adress']?></a><br><br>
                            </div>
                        </div>
                        <p class="informations-two">
                            &#127795; <?=$newRequest['categories']?><br>
                            &#128207; <?=$newRequest['length']?> cm<br>
                            &#128668; <?=$newRequest['quantity']?> steres
                        </p>
                        <div class="admin-button">
                            <a href="stock-update.php?id=<?=$newRequest['id_request']?>" class="admin-btn">Accepter</a>
                            <a href="delete-request.php?id=<?=$newRequest['id_request']?>" class="admin-btn">Refuser</a>
                        </div>
                    </div>
                </div>
<!---------------------------------------------------------------------------------------------------------------------------------------------------------->
<?php
}  
echo '</div>';
?>
<!---------------------------------------------------------------------------------------------------------------------------------------------------------->
            </div>
        </section>
    </main>
</body>
</html>
<!---------------------------------------------------------------------------------------------------------------------------------------------------------->
<?php    
    } else { foreach($repSearch AS $repSearchAll){
    echo '<div class="card-ordered dflex">';
?>
<!---------------------------------------------------------------------------------------------------------------------------------------------------------->
                <div id="article-card-result-search-ok" class="article-card dflex fdcolumn">
                    <div class="article-card-text">
                        <h3><?=$repSearchAll['firstname']?> <?=$repSearchAll['name']?></h3>
                        <p class="p-id">
                            Commande N° <?=$repSearchAll['id_request']?>
<!---------------------------------------------------------------------------------------------------------------------------------------------------------->
<?php
if($repSearchAll['accepted'] === 1) {
    echo '(Acceptée)';
} else {
    echo '(En attente)';
}
?><br>
<!---------------------------------------------------------------------------------------------------------------------------------------------------------->   
                            &#128197; <?=$repSearchAll['date']?>                     
                        </p>
                        <p class="informations-one">
                            &#128222; <?=$repSearchAll['phone']?><br>
                            <a href="mailto:<?=$repSearchAll['mail']?>" target="_blank">&#128232; <?=$repSearchAll['mail']?></a><br>
                            <a href="https://www.google.fr/maps/place/<?=$repSearchAll['adress']?>" target="_blank">&#127968; <?=$repSearchAll['adress']?></a><br><br>
                        </p>
                        <p class="informations-two">
                            &#127795; <?=$repSearchAll['categories']?><br>
                            &#128207; <?=$repSearchAll['length']?> cm<br>
                            &#128668; <?=$repSearchAll['quantity']?> stere
                        </p>
                        <div class="admin-button">
                            <a href="stock-update.php?id=<?=$repSearchAll['id_request']?>" class="admin-btn">Accepter</a>
                            <a href="delete-request.php?id=<?=$repSearchAll['id_request']?>" class="admin-btn">Refuser</a>
                        </div>
                    </div>
                </div>
<!---------------------------------------------------------------------------------------------------------------------------------------------------------->
<?php
echo '</div>';
}}
    } else { header('Location: login.php');}
?>