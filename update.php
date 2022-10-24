<?php
session_start();
require_once 'connect.php';
/*--------------------------------------------------*/
$id = $_GET['id'];

$article = $db->query("SELECT articles.id_article, articles.title, articles.description, articles.price, articles.stock, articles.categories, articles.photo, articles.alt_photo, articles.length 
FROM articles WHERE articles.id_article = '$id'");
$article = $article->fetch(PDO::FETCH_ASSOC);

$catQuery = $db->query("SELECT `id_category`, `name_category` FROM `categories`");
$cat = $catQuery->fetchAll(PDO::FETCH_ASSOC);

/*--------------------------------------------------*/
ob_start();
/*--------------------------------------------------*/
if(isset($_SESSION['login'])) {
?>
<!--------------------------------------------------->
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
    <title>BOIS PASCAL - Modifier un article</title>
</head>
<body>
    <section id="add-new">
        <div class="container">
            <a href="admin.php" class="arrow">< Retour aux articles</a>
            <form action="dbupdate.php" method="POST" class="dflex fdcolumn" enctype="multipart/form-data">
                <div class="dflex">
                    <div class="form-wrapper">
                        <input type="hidden" name="id_article" id="id_article" value="<?= $article['id_article']?>">
                        <div class="dflex fdcolumn">
                            <label for="title">Ajouter un note &#8595;</label>
                            <input type="text" name="title" id="title" value="<?= $article['title']?>">
                        </div>
                        <!--
                        <div class="dflex fdcolumn">
                            <label for="description">Ajouter la description &#8595;</label>
                            <textarea name="description" id="description" cols="30" rows="5"></textarea>
                        </div>
                        -->
                        <div class="dflex fdcolumn">
                            <label for="price">Modifier le prix &#8595;</label>
                            <input type="text" name="price" id="price" value="<?= $article['price']?>">
                        </div>
                        <div class="dflex fdcolumn">
                            <label for="stock">Modifier le stock (quantité de bois totale) &#8595;</label>
                            <input type="text" name="stock" id="stock" value="<?= $article['stock']?>">
                        </div>
                        <div class="dflex fdcolumn">
                            <label for="length">Changer la taille &#8595;</label>
                            <input type="text" name="length" id="length" value="<?= $article['length']?>">
                        </div>
                    </div>
                    <div class="form-wrapper">
                        <p class="label">Changer la catégorie (type de bois) &#8595;</p>
                        <div class="dflex fdcolumn radio-wrapper">
<!---------------------------------------------------------------------------------------------------------->
<?php
foreach($cat as $c) {
    if($c['id_category']===$article['categories']) {
?>
<!---------------------------------------------------------------------------------------------------------->
                            <div>
                                <input type="radio" id="<?=$c['name_category']?>"
                                name="categories" value="<?=$c['id_category']?>" checked>
                                <label for="<?=$c['name_category']?>"><?=$c['name_category']?></label>
                            </div>       
<!---------------------------------------------------------------------------------------------------------->
<?php
    } else {
?>
<!---------------------------------------------------------------------------------------------------------->
                            <div>
                                <input type="radio" id="<?=$c['name_category']?>"
                                name="categories" value="<?=$c['id_category']?>">
                                <label for="<?=$c['name_category']?>"><?=$c['name_category']?></label>
                            </div>
<!---------------------------------------------------------------------------------------------------------->
<?php
};
}
?>
<!---------------------------------------------------------------------------------------------------------->
                        </div>
                        <!--
                        <div class="dflex fdcolumn">
                            <label for="img">Choisir une autre image &#8595;</label>
                            <input type="file" name="img" id="img" value="<?=$article['photo']?>">
                        </div>
                        <div class="dflex fdcolumn">
                            <label for="alt">Changer la description de l'image &#8595;</label>
                            <input type="text" name="alt" id="alt" value="<?= $article['alt_photo']?>">
                        </div>
                        -->
                    </div>
                </div>
                <input type="submit" name="submit" value="Sauvegarder" class="add-input">
            </form>
        </div>
    </section>
</body>
</html>
<!---------------------------------------------------------------------------------------------------------->
<?php

} else {
    header('Location: login.php');
}