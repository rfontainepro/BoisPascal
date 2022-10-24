<?php
session_start();
require_once 'connect.php';

$id = $_GET['id'];

$del = $db->query("DELETE FROM `articles` WHERE `id_article` = '$id'");
if($del){
    header('Location: admin.php');
} else {
    echo "Il y a un souci";
};
