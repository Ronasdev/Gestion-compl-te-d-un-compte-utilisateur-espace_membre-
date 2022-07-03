<?php
session_start();
require_once('./includes/db.php');
require_once('./includes/functions.php');

if (!empty($_POST)) {

    $errors = [];

    // Pseudo
    if (empty($_POST['username']) || !preg_match("#^[a-zA-Z0-9_]+$#", $_POST['username'])) {
        $errors['username'] = "Votre pseudo n'est pas valide";
    } else {
        // SELECT * FROM users WHERE username = post
        $query = "SELECT * FROM users WHERE username = ?";
        $req = $pdo->prepare($query);
        $req->execute([$_POST['username']]);
        if ($req->fetch()) {
            $errors['username'] = "Ce pseudo n'est plus disponible";
        }
    }

    // Email
    if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Votre email n'est pas valide";
    } else {
        // SELECT * FROM users WHERE email = post
        $query = "SELECT * FROM users WHERE email = ?";
        $req = $pdo->prepare($query);
        $req->execute([$_POST['email']]);
        if ($req->fetch()) {
            $errors['email'] = "Cet email est déjà pris";
        }
    }

    // Password
    if (empty($_POST['password']) || $_POST['password'] !== $_POST['password_confirm']) {
        $errors['password'] = "Vous devez rentrez un mot de passe valide et confirmé";
    }

    if (empty($errors)) {
        $query = "INSERT INTO users(username,email,password,confirmation_token) VALUES(?,?,?,?)";
        $req = $pdo->prepare($query);
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        $token = generateToken(100);

        $req->execute([$_POST['username'], $_POST['email'], $password, $token]);
        $userId = $pdo->lastInsertId();

        $mail = $_POST['email'];
        $subject = "Confirmation du compte";
        $message = "Afin de confirmer votre compte,merci de cliquer sur ce lien\n\n
        http://localhost/gestion_compte_utilisateur/confirm.php?id=$userId&token=$token";

        mail($mail, $subject, $message);

        $_SESSION['flash']['success'] = "Compte créé avec sucèss. Veillez vérifier votre boite mail afin de confirmer votre compte";

        header("Location: login.php");
        exit();
    }
}
?>
<?php
require_once './includes/header.php';
?>
<div class="col-md-8 col-md-offset-2">
    <h1 style="color: #fff;">S'inscrire</h1>
    <form action="" method="post">
        <fieldset>
            <div class="form-group">
                <label for="pseudo">Nom d'utilisateur</label>
                <input type="text" id="pseudo" class="form-control" name="username">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" class="form-control" name="email">
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" class="form-control" name="password">
            </div>
            <div class="form-group">
                <label for="password">Confirmer votre mot de passe</label>
                <input type="password" id="password" class="form-control" name="password_confirm">
            </div>
            <input type="submit" class="btn btn-primary" value="S'inscrire">
        </fieldset>
    </form>
</div>

<?php
require_once './includes/footer.php';
?>