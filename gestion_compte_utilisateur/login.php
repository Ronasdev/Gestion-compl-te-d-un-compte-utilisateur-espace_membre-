<?php
session_start();

require_once './includes/db.php';
require_once './includes/functions.php';

reconnect_auto();

if (!empty($_POST) && !empty($_POST['username']) && !empty($_POST['password'])) {
    $query = "SELECT * FROM users WHERE (username = :username OR email = :username) AND confirmed_at IS NOT NULL";
    $req = $pdo->prepare($query);
    $req->execute(['username' => $_POST['username']]);
    $user = $req->fetch();

    if ($user && password_verify($_POST['password'], $user->password)) {
        $_SESSION['auth'] = $user;
        $_SESSION['flash']['success'] = "Connexion éffectuée avec sucess";

        if (isset($_POST['remember'])) {
           $remember_token = generateToken(100);
           $query = "UPDATE users SET remember_token = ? WHERE id = ?";
           $pdo->prepare($query)->execute([$remember_token,$user->id]);

           setcookie("remember",$user->id . "::".$remember_token. sha1($user->id ."Ronasdev"),time()+ 60* 60 * 24 * 7);
        }

        header("Location: Index.php");
        exit();
    }else{
        $_SESSION['flash']['danger'] = "Identifiant ou mot de passe incorrect";
    }
}
?>
<?php require_once './includes/header.php'; ?>
<div class="col-md-8 col-md-offset-2">
    <h1 style="color: #fff;">Se connecter</h1>
    <form action="" method="post">
        <fieldset>
            <div class="form-group">
                <label for="pseudo">Nom d'utilisateur ou Email</label>
                <input type="text" id="pseudo" class="form-control" name="username">
            </div>
            <div class="form-group">
                <label for="password">Mot de passe <a href="remember.php">(J'ai oublié mon mot de passe)</a> </label>
                <input type="password" id="password" class="form-control" name="password">
            </div>
            <div class="form-group">
                <label for="password"> <input type="checkbox" name="remember" value="1"> Se souvenir de moi</label>

            </div>
            <input type="submit" class="btn btn-primary" value="Se connecter">
        </fieldset>
    </form>
</div>

<?php
require_once './includes/footer.php';
?>