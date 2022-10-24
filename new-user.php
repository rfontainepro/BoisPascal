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
    <title>BOIS PASCAL - Nouvel utilisateur</title>
</head>
<body>
    <section id="login">
        <div class="container dflex">
            <form action="db-new-user.php" method="POST">
                <div class="form-wrapper">
                    <h3 style="margin-bottom: 35px">Veuillez remplir les champs suivants :</h3>
                    <div>
                        <label for="login">Votre nom d'utilisateur :</label>
                        <input required type="text" name="login" id="login">
                    </div>
                    <div>
                        <label for="email">Votre email :</label>
                        <input required type="text" pattern="[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([_\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})" name="email" id="email">
                    </div>
                    <div>
                        <label for="password">Votre mot de passe :</label>
                        <input required type="password" name="password" id="password">
                    </div>
                </div>
                <input type="submit" name="submit" value="Create account" class="add-input">
            </form>       
        </div>
    </section>
</body>
</html>