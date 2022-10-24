<?php
/* Cacher les messages d'erreurs PHP */
ini_set('display_errors', 'off');
/*---------------------------------------------------------------------------------------------------------*/
session_start();
/*---------------------------------------------------------------------------------------------------------*/
require_once 'connect.php'; // Fichier contenant les paramêtres pour se connecter à la DB
require_once 'apikey.php'; // Fichier contenant la clef de l'API Météo
/*---------------------------------------------------------------------------------------------------------*/
if(isset($_GET['asc'])) {
    $order = $db->query('SELECT * FROM `articles` ORDER BY `id_article` ASC');
} else {
    $order = $db->query('SELECT * FROM `articles` ORDER BY `id_article` DESC');
}
/*-----------------------------------------------------------------------------------------*/
// Afficher le nombre total de demandes dans la DB (non traitées)
$all = $db->prepare("SELECT COUNT(*) FROM requests WHERE accepted = 0;");
$all->execute();
$result = $all->fetch();

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

/*---------------------------------------------------------------------------------------------------------------*/
ob_start();
/*-----------------------------------------------------------------------------------------*/
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
echo "<h3 style='margin-top:30px; padding-left: 10px; background-color: #00000021'><span>&#128197; " . date("D d M") . " - &#128338; " . date("H:i") . " - </span>[&#127777; {$api_result['current']['temperature']}℃ - &#127783; {$api_result['current']['precip']}mn ] - " . $_SESSION['login'] . "</h3>";
?>
<!---------------------------------------------------------------------------------------------------------------------------------------------------------->
                <nav class="navigation_cat dflex">
                    <ul>
                        <li>
                            &#128270;
                        </li>
                        <li>
                            <a href="admin.php" <?= !isset($_GET['asc']) ? 'class="category-active"' : '' ?>>Articles récents</a>
                        </li>
                        <li>
                            <a href="admin.php?asc" <?= isset($_GET['asc']) ? 'class="category-active"' : '' ?>>Articles anciens</a>
                        </li>
                    </ul>
                    <div class="logout">
<!---------------------------------------------------------------------------------------------------------------------------------------------------------->
<?php
/* Redirection sur la bonne page si ADMIN+ */
if($_SESSION['login'] === 'romain') {
    echo '<a href="request-admin.php" class="dflex">';
} else {
    echo '<a href="request.php" class="dflex">';
}
?>
<!---------------------------------------------------------------------------------------------------------------------------------------------------------->
                            <img src="img/logout.svg" alt="Log out">
                            <p class="p-id">Gérer les demandes (<?=$result['0']?>)</p>
                        </a>
                    </div>
                    <div class="logout">
                        <a href="logout.php" class="dflex">
                            <img src="img/logout.svg" alt="Log out">
                            <p class="p-id">Se déconnecter</p>
                        </a>
                    </div>
                </nav>
                <!-- <a href="new-article.php" class="admin-btn add-btn">Ajouter un article +</a> -->
                <!-- Pour ajouter un article / Fonction pour plus tard -->
<!---------------------------------------------------------------------------------------------------------------------------------------------------------->
<?php
echo '<div class="card-ordered dflex">';
while ($orderX = $order->fetch(PDO::FETCH_ASSOC)) {
?>
<!---------------------------------------------------------------------------------------------------------------------------------------------------------->
                    <div class="article-card dflex fdcolumn">
                        <img src="<?=$orderX['photo']?>" alt="<?=$orderX['alt_photo']?>">
                        <div class="article-card-text">
                            <h3><?=$orderX['title']?></h3>
                            <p class="p-id">
                                Article id N° <?=$orderX['id_article']?>
                            </p>
                            <p class="card-price">
                                Prix: <?=$orderX['price']?> euros
                            </p>
                            <p class="p-id">
                                En stock : <?=$orderX['stock']?>
                            </p>
                            <div class="admin-button">
                                <a href="update.php?id=<?=$orderX['id_article']?>" class="admin-btn">Modifier</a>
                                <a href="delete-article.php?id=<?=$orderX['id_article']?>" class="admin-btn">Supprimer</a>
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
} else {
    header('Location: login.php');
}