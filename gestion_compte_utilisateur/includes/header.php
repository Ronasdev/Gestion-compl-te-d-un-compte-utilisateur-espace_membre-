<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de compte utilisateur</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body style="background:  #000709e6; color:#fff;">

    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Compte Utilisateur</a>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
                <ul class="nav navbar-nav">
                    <?php if (isset($_SESSION['auth'])) : ?>
                        <li><a href="logout.php">Se deconnecter</a></li>
                    <?php else : ?>
                        <li class="active"><a href="register.php">S'enregistrer <span class="sr-only"></span></a></li>
                        <li><a href="login.php">Se connecter</a></li>
                    <?php endif; ?>
                </ul>
                <form class="navbar-form navbar-right" role="search">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Search">
                    </div>
                    <button type="submit" class="btn btn-default">Rechercher</button>
                </form>
            </div>
        </div>
    </nav>
    <div class="container col-md-8 col-md-offset-2">
        <?php if (isset($_SESSION['flash'])) : ?>
            <?php foreach ($_SESSION['flash'] as $type => $message) : ?>
                <div class="alert alert-<?= $type ?>">
                    <?= $message ?>
                </div>
            <?php endforeach; ?>
            <?php unset($_SESSION['flash']) ?>
        <?php endif; ?>