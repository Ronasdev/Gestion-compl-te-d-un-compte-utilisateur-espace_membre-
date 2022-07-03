<?php
require_once './includes/db.php';
session_start();

if (isset($_GET['id']) && isset($_GET['token'])) {

    $userId = $_GET['id'];
    $token = $_GET['token'];
    $query = "SELECT * FROM users WHERE id = ? AND reset_token = ? AND reset_at > DATE_SUB(NOW(), INTERVAL 30 MINUTE) AND confirmed_at IS NOT NULL";
    $req = $pdo->prepare($query);
    $req->execute([$userId, $token]);

    $user = $req->fetch();

    if ($user) {
        if (!empty($_POST)) {
            if (!empty($_POST['password']) || $_POST['password'] == $_POST['password_confirm']) {
                $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
                $query = "UPDATE users SET password = ?,reset_token = NULL,reset_at = NULL WHERE id = ?";
                $pdo->prepare($query)->execute([$password, $userId]);

                $_SESSION['flash']['success'] = "Votre mot de passe a bien été mise à jours";
                $_SESSION['auth'] = $user;
                header('Location: index.php');
                exit();
            } else {
                $_SESSION['flash']['danger'] = "Les deux mots de passes ne correspondent pas !";
            }
        }
    } else {
        $_SESSION['flash']['danger'] = "Ce token n'est plus valide";
        header('Location: login.php');
        exit();
    }
} else {
    header('Location: login.php');
    exit();
}



?>

<?php require_once './includes/header.php'; ?>

<div class="col-md-8 col-md-offset-2">
    <h1 style="color: #fff;">Réinitialiser votre mot de passe</h1>

    <form action="" method="post">
        <fieldset>
            <div class="from-group">
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" class="form-control">
            </div>
            <div class="from-group">
                <label for="password">Confirmation du mot de passe</label>
                <input type="password" name="password_confirm" id="password" class="form-control">
            </div>
            <div class="form-group" style="margin-top: 20px;">
                <input class="btn btn-primary" type="submit" value="Réinitialiser votre mot de passe">
            </div>
        </fieldset>
    </form>
</div>

<?php
require_once './includes/footer.php';
?>