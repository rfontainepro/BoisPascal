<?php
session_start();
require_once 'connect.php';
/*---------------------------------------------------------------------------*/
$id = $_GET['id'];
/*---------------------------------------------------------------------------*/
/* Afficher les types de bois */
$catQuery = $db->query("SELECT `id_category`, `name_category` FROM `categories`");
$cat = $catQuery->fetchAll(PDO::FETCH_ASSOC);

/* Rechercher information liées à un ID pour pré-remplir les informations dans le formulaire de commande */
$reqValue = $db->query("SELECT articles.id_article, articles.title, articles.photo, articles.alt_photo, articles.length, articles.price, articles.stock, categories.id_category, categories.name_category FROM articles INNER JOIN categories ON articles.categories = categories.id_category WHERE id_article = $id;");
$repValue = $reqValue->fetch(PDO::FETCH_ASSOC);

/* Sélection du bouton radio par défaut suivant la taille du bois */
if($repValue['length'] === 50) {
    $checkedA = "checked";
    $checkedB = "";
    $checkedC = "";
};

if($repValue['length'] === 33) {
    $checkedA = "";
    $checkedB = "checked";
    $checkedC = "";
};

if($repValue['length'] === 25) {
    $checkedA = "";
    $checkedB = "";
    $checkedC = "checked";
};

/* Sélection du bouton radio par défaut suivant la catégorie de bois */
if($repValue['id_category'] === 1) {
    $checkedZ = "checked";
    $checkedY = "";
    $checkedX = "";
    $checkedW = "";
};

if($repValue['id_category'] === 2) {
    $checkedZ = "";
    $checkedY = "checked";
    $checkedX = "";
    $checkedW = "";
};

if($repValue['id_category'] === 3) {
    $checkedZ = "";
    $checkedY = "";
    $checkedX = "checked";
    $checkedW = "";
};

if($repValue['id_category'] === 4) {
    $checkedZ = "";
    $checkedY = "";
    $checkedX = "";
    $checkedW = "checked";
};

/*---------------------------------------------------------------------------*/
/* Prix total suivant la quantité souhaitée par le client */

/*
/* Chêne
$PriceT = $reqValue['price'] x
/* Hêtre
$PriceT = $reqValue['price'] x
/* Frêne
$PriceT = $reqValue['price'] x
/* Erable
$PriceT = $reqValue['price'] x
*/

/*---------------------------------------------------------------------------*/
?>
<!---------------------------------------------------------------------------->
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
    <title>BOIS PASCAL - Passer une commande</title>
</head>
<body>
    <section id="add-new">
        <div class="container">
            <a href="index.php" class="arrow"> < Accueil </a>
            <form action="dbnew-request.php" method="POST" class="dflex fdcolumn" enctype="multipart/form-data">
                <div class="dflex">
                    <div class="form-wrapper">
                        <div class="dflex fdcolumn">
                            <label for="name">Nom &#8595;</label>
                            <input required type="text" name="name" id="name" pattern="^[\s\S]{4,}" title="Votre nom SVP" maxlength=20 placeholder="Nom">
                        </div>
                        <div class="dflex fdcolumn">
                            <label for="firstname">Prénom &#8595;</label>
                            <input required type="text" name="firstname" id="firstname" pattern="^[\s\S]{4,}" title="Votre prénom SVP" maxlength=20 placeholder="Prénom">
                        </div>
                        <div class="dflex fdcolumn">
                            <label for="phone">Téléphone &#8595;</label>
                            <input required type="text" name="phone" id="phone" pattern="^(?:(?:\+|00)33[\s.-]{0,3}(?:\(0\)[\s.-]{0,3})?|0)[1-9](?:(?:[\s.-]?\d{2}){4}|\d{2}(?:[\s.-]?\d{3}){2})$" title="Veuillez saisir correctement votre numéro SVP" placeholder="Téléphone">
                        </div>
                        <div class="dflex fdcolumn">
                            <label for="mail">Mail &#8595;</label>
                            <input required type="text" name="mail" id="mail" pattern="[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([_\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})" title="Veuillez saisir correctement votre email SVP" maxlength=35 placeholder="Mail">
                        </div>
                        <div class="dflex fdcolumn">
                            <label for="adress">Adresse &#8595;</label>
                            <input required type="text" name="adress" id="adress" pattern="^[\s\S]{10,}" title="Votre adresse SVP" maxlength=65 placeholder="Adresse">
                        </div>
                    </div>
<!---------------------------------------------------------------------------->
                    <div class="form-wrapper">
                        <p class="label">Choisir le type de bois &#8595;</p>
                        <div class="dflex fdcolumn radio-wrapper">
                            <div>
                                <input required type="radio" id="Chêne" name="categories" value="Chêne" <?php echo $checkedZ ?>>
                                <label for="Chêne">Chêne</label>
                            </div>
                            <div>
                                <input required type="radio" id="Hêtre" name="categories" value="Hêtre" <?php echo $checkedY ?>>
                                <label for="Hêtre">Hêtre</label>
                            </div>
                            <div>
                                <input required type="radio" id="Frêne" name="categories" value="Frêne" <?php echo $checkedX ?>>
                                <label for="Frêne">Frêne</label>
                            </div>
                            <div>
                                <input required type="radio" id="Erable" name="categories" value="Erable" <?php echo $checkedW ?>>
                                <label for="Erable">Erable</label>
                            </div>
                        </div>
                        <p class="label">Choisir la taille &#8595;</p>
                        <div class="dflex fdcolumn radio-wrapper">
                            <div>
                                <input required type="radio" id="50" id="length" name="length" value="50" <?php echo $checkedA ?>>
                                <label for="50">50 cm</label>
                            </div>
                            <div>
                                <input required type="radio" id="33" id="length" name="length" value="33" <?php echo $checkedB ?>>
                                <label for="33">33 cm</label>
                            </div>
                            <div>
                                <input required type="radio" id="25" id="length" name="length" value="25" <?php echo $checkedC ?>>
                                <label for="25">25 cm</label>
                            </div>
<!---------------------------------------------------------------------------->
                            <div class="dflex fdcolumn">
                                <label for="quantity">Quantité &#8595;</label>
                                <input required type="text" name="quantity" id="quantity" pattern="^[0-9]*$" title="La quantité SVP" maxlength="2" placeholder="Quantité">     
                            </div>
                        </div>
                    </div>
                </div>
                <input type="submit" name="submit" value="Passer commande !" class="add-input">
            </form>
        </div>
    </section>
</body>
</html>