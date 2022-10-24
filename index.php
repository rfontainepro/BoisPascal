<?php
require_once 'connect.php';
ob_start();
/* -------------------------------------------------------------- Personnaliser le Titre + Message ------------------------------------------------------- */
$reqFYI = $db->query('SELECT * FROM `fyi`');
$FYI = $reqFYI->fetch(PDO::FETCH_ASSOC);
/*---------------------------------------------------------------------------------------------------------------------------------------------------------*/
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
    <link href='https://fonts.googleapis.com/css?family=DynaPuff' rel='stylesheet'>
    <!------------------------------------>
    <link rel="icon" type="image/png" href="img/favicon/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="img/favicon/favicon-16x16.png" sizes="16x16" />
    <!------------------------------------>
    <link rel="stylesheet" href="main.css">
    <title>BOIS PASCAL</title>
</head>
<!---------------------------------------------------------------------------------------------------------------------------------------------------------->
<body>
    <header>
        <div class="container">
            <nav class="navigation">
                <ul>
                    <figure>
                        <a href="index.php">
                            <img id="logo" src="img/logo.gif" alt="Logo">
                        </a>
                    </figure>
                </ul>
                <div id="effect-btn">
                    <a href="new-request.php" class="admin-btn add-btn">Passer une commande</a>
                    <!-- <a href="follow-request.php" class="admin-btn add-btn">Suivi de commande</a> -->
                </div>
            </nav>
        </div>
    </header>
<!---------------------------------------------------------------------------------------------------------------------------------------------------------->
    <main>
        <section id="entete">
            <!-- Video -->
            <video autoplay muted loop id="background_video_header">
                <source src="img/chain_saw.mp4" alt= Video type="video/mp4">
            </video>
        </section>

        <div id="title">
            <h1 class="tac"><?php echo $FYI['title'] ?></h1>
            <p class="pub-text tac"><?php echo $FYI['text'] ?></p>
        </div>
<!---------------------------------------------------------------------------------------------------------------------------------------------------------->
    <?php include 'catalogue.php'; ?>
<!---------------------------------------------------------------------------------------------------------------------------------------------------------->
    </main>
<!---------------------------------------------------------------------------------------------------------------------------------------------------------->
    <footer>
        <!-- Video -->
        <video autoplay muted loop id="background_video_footer">
            <source src="img/chain_saw.mp4" alt= Video type="video/mp4">
        </video>
        <!-- Signature -->
        <a href="https://www.romain.app" target="_blank" class="adminX-btn addX-btn"><p>&copy; Romain.app</p></a>
    </footer>
</body>