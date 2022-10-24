<?php 
session_start();
require_once 'connect.php';
/*------------------------------------------------------------------------------------------------------*/
if(isset($_POST['submit'])) {

    /*
    $path = './img/';
    $arrayType = ["jpg" => 'image/jpg', "jpeg" => 'image/jpeg'];
    $name = basename($_FILES['img']['name']);
    $insertname = "img/" . $name;

    if(in_array($_FILES['img']['type'], $arrayType)){
        $newImg = move_uploaded_file($_FILES['img']['tmp_name'], $path.$name);
    } else {
        echo "Choisir une image au format JPG/JPEG";
    };
    */

    $id = $_POST['id_article'];
    $title = $_POST['title'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $length = $_POST['length'];
    $categories = $_POST['categories'];

    /* $description = $_POST['description']; */
    /*$alt = $_POST['alt']; */

    /* 
    if($newImg) {
        $editedPhoto = $db->query("UPDATE `articles` SET `photo` = '$insertname' WHERE `id_article` = '$id'");
    } else {
        echo "ok";
    }
    */

/*------------------------------------------------------------------------------------------------------*/
var_dump($_POST);
/*------------------------------------------------------------------------------------------------------*/  

    /* $edited = $db->prepare("UPDATE `articles` SET title = :title, alt_photo = :alt, description = :description, length = :length, categories = :categories, price = :price, stock = :stock */
    $edited = $db->prepare("UPDATE `articles` SET title = :title, length = :length, categories = :categories, price = :price, stock = :stock 
    WHERE id_article = :id");

    $edited->execute([
        'id' => $id,
        'title' => $title,
        /* 'alt' => $alt, */
        /* 'description' => $description, */
        'length' => $length,
        'price' => $price,
        'stock' => $stock,
        'categories' => $categories,
    ]);
};

/*------------------------------------------------------------------------------------------------------*/
if($edited) {
    header('Location: admin.php');
} else {
    /*------------------------------------------------------*/
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

    $to       = "support@boispascal.com"; /* A qui */
    $headers .= 'From: Support <support@boispascal.com>' . "\r\n"; /* De qui */
    $subject  = "Avertissement : Erreur DB-UPDATE sur boispascal.com"; /* Titre du mail */

    mail($to, mb_encode_mimeheader($subject), mb_encode_mimeheader($messageErreur), $headers);
    /*------------------------------------------------------*/

    echo "Oops, il y a eu une erreur !"; /* Message d'erreur pour le client */
};