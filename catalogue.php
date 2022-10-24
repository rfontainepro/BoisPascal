<?php
require_once 'connect.php';
/*-----------------------------------------------------------------------------------------*/
/* ---------------------------- SEARCH --------------------------------------------------- */

// Récupération de l'input de la barre de recherche pour la stocker dans la variable $search
// $search = $_GET["search"];

// Afficher le nombre total d'articles dans la DB
$all = $db->prepare("SELECT COUNT(*) FROM articles");
$all->execute();
$result = $all->fetch();

/* Recherche sur le type de bois et sa taille */
// $reqSearch = $db->query("SELECT articles.length, articles.stock, categories.name_category FROM articles INNER JOIN categories ON articles.categories = categories.id_category HAVING categories.name_category = '$search' AND length = '$search';");
// $repSearch = $reqSearch->fetch(PDO::FETCH_ASSOC);

/*-----------------------------------------------------------------------------------------*/

$req = $db->query('SELECT articles.id_article, articles.title, articles.photo, articles.alt_photo, articles.length, articles.price, articles.stock, categories.id_category, categories.name_category FROM articles INNER JOIN categories ON articles.categories = categories.id_category ORDER BY id_article ASC');
$catQuery = $db->query("SELECT id_category, name_category FROM categories");
$catList = $catQuery->fetchAll(PDO::FETCH_ASSOC);

/*-----------------------------------------------------------------------------------------*/

$lengthA = $db->query('SELECT * FROM `articles` WHERE `length` LIKE 50 ORDER BY `articles`.`id_article` ASC');
$AlengthAll = $lengthA->fetchAll(PDO::FETCH_ASSOC);

/* ------ */

$lengthB = $db->query('SELECT * FROM `articles` WHERE `length` LIKE 33 ORDER BY `articles`.`id_article` ASC');
$BlengthAll = $lengthB->fetchAll(PDO::FETCH_ASSOC);

/* ------ */

$lengthC = $db->query('SELECT * FROM `articles` WHERE `length` LIKE 25 ORDER BY `articles`.`id_article` ASC');
$ClengthAll = $lengthC->fetchAll(PDO::FETCH_ASSOC);

/* ------ */
ob_start();

?>
<!----------------------------------------------------------------------------------------------------------->
    <div id="background_cards">
        <section id="list">
            <div class="container">
                <nav class="navigation_cat">
                    <ul>
                        <li>
                            <a href="index.php"> &#127795;[<?php echo($result['0']) ?>] Tous</a>
                        </li>
<!----------------------------------------------------------------------------------------------------------->         
<?php
foreach($catList as $c) {
?>
<!----------------------------------------------------------------------------------------------------------->   
                        <li>
                            <a href="index.php?cat=<?=$c['name_category']?>"<?= (isset($_GET['cat']) && $_GET['cat'] === $c['name_category']) ? 'class="category-active"' : ''?>><?=$c['name_category']?></a>
                        </li>
<!----------------------------------------------------------------------------------------------------------->
<?php
}
/*-----------------------------------------*/
include 'follow-request.php';
/*-----------------------------------------*/
?>
<!----------------------------------------------------------------------------------------------------------->
                        <!-- SEARCH
                        <li>
                            <form id="search-article" method="GET" >
                                <input maxlength=20 type="text" name="search" value="<?php echo $search ?>" placeholder="Rechercher parmis <?php echo($result['0']); ?> Article(s)" />
                                <input id="invisible" type="submit" value="search" />
                            </form>
                        </li>
                        -->
                    </ul>
                </nav>
            </div>
        </section>
        <section class="latest list__catalogue">
            <div class="container dflex fdcolumn">
<!----------------------------------------------------------------------------------------------------------->
<?php
if(isset($_GET['cat'])) {
    $cat = $_GET['cat'];
    $listQuery = $db->query("SELECT articles.id_article, articles.title, articles.photo, articles.alt_photo, articles.length, articles.price, articles.stock, categories.id_category, categories.name_category FROM articles INNER JOIN categories ON articles.categories = categories.id_category WHERE name_category = '$cat'");
    $list = $listQuery->fetchAll(PDO::FETCH_ASSOC);
    echo '<div class="card-ordered dflex">';
    foreach ($list as $l) {
?>
<!----------------------------------------------------------------------------------------------------------->
                <div class="article-card dflex fdcolumn">
                    <a href="new-request-value.php?id=<?=$l['id_article']?>">
                        <img src="<?=$l['photo']?>" alt="<?=$l['alt_photo']?>">
                        <div class="article-card-text">
                            <!-- <h3><?=$l['title']?></h3> -->
                            <a href="index.php?cat=<?=$l['name_category']?>">
                                <p class="card-category">
                                    <?=$l['name_category']?>
                                </p>
                            </a>
                            <p class="card-length">
                                Dimension: <?=$l['length']?> cm
                            </p>
                            <p class="card-price">
                                Prix: <?=$l['price']?> euro
                            </p>
                            <p class="card-stock">
                                En stock: <?=$l['stock']?> stères
                            </p>
                        </div>
                    </a>
                </div>
<!----------------------------------------------------------------------------------------------------------->
<?php
}; 
?>
<!----------------------------------------------------------------------------------------------------------->
<?php    
} else {
    echo '<div class="card-ordered dflex">';
    while ($reqX = $req->fetch(PDO::FETCH_ASSOC)) {
?>
<!----------------------------------------------------------------------------------------------------------->
                <div class="article-card dflex fdcolumn">
                    <a href="new-request-value.php?id=<?=$reqX['id_article']?>">
                        <img src="<?=$reqX['photo']?>" alt="<?=$reqX['alt_photo']?>">
                        <div class="article-card-text">
                            <!-- <h3><?=$reqX['title']?></h3> -->
                            <a href="index.php?cat=<?=$reqX['name_category']?>">
                                <p class="card-category">
                                    <?=$reqX['name_category']?>
                                </p>
                            </a>
                            <p class="card-length">
                                Dimension: <?=$reqX['length']?> cm
                            </p>
                            <p class="card-price">
                                Prix: <?=$reqX['price']?> euros
                            </p>
                            <p class="card-stock">
                                En stock: <?=$reqX['stock']?> stères
                            </p>
                        </div>
                    </a>
                </div>
<!----------------------------------------------------------------------------------------------------------->
<?php
}  
echo '</div>';
};
?>
<!----------------------------------------------------------------------------------------------------------->
            </div>
        </section>
    </div>