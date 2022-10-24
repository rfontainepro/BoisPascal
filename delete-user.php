<?php
session_start();
require_once 'connect.php';
/*------------------------------------------------------*/
$id = $_GET['id'];

/* Suppression du compte utilisateur */
$del = $db->query("DELETE FROM `admin` WHERE `id_admin` = '$id'");
/*------------------------------------------------------*/
header('Location: request-admin.php');
echo "Utilisateur supprimé ! ";