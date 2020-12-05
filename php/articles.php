<?php
require_once 'config.php';
require_once 'functions.php';


if (!empty($_GET['id'])) {
    $id = htmlspecialchars($_GET['id']);

    $article = $bdd->prepare('SELECT *, DATE_FORMAT(datetime_post, "%d %M %Y") date_formatee FROM articles WHERE id = ?');
    $article->execute([$id]);

    $article = $article->fetch(PDO::FETCH_ASSOC);

    if (!$article) {
        header('Location: index.php');
        exit();
    }
    $message_commentaire = '';
    if (isset($_POST['commentaire'])) {
        if (isset($_POST['nom'], $_POST['email'], $_POST['contenu'])) {

            $nom = htmlspecialchars($_POST['nom']);
            $email = htmlspecialchars($_POST['email']);
            $contenu = htmlspecialchars($_POST['contenu']);

            if (!empty($nom) AND !empty($email) AND !empty($contenu)) {
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

                    $insert = $bdd->prepare('INSERT INTO commentaires(id_article,nom,email,contenu) VALUES (:id_article,:nom,:email,:contenu)');
                    $res = $insert->execute([
                        ':id_article' => $article['id'],
                        ':nom' => $nom,
                        ':email' => $email,
                        ':contenu' => $contenu

                    ]);

                    if ($res) {
                        $message_commentaire = "votre commentaire a bien ete poste";
                    } else {

                        $message_commentaire = "une erreur est survenue";
                    }
                } else {
                    $message_commentaire = "votre addresse n est pas conforme";
                }
            } else {


                $message_commentaire = "veuillez renseigner tous les champs";
            }
        } else {

            $message_commentaire = "veuillez renseigner tous les champs";
        }
    }


    $commentaires = $bdd->prepare('SELECT * FROM commentaires WHERE id_article =? ORDER BY id DESC');
    $commentaires->execute([$article['id']]);
} else {

    header('location :index.php');
    exit();
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css"> -->
    <link rel="stylesheet" href="../css/style.css">
    <title><?= $article['titre'] ?></title>
</head>

<body>
    <?php include_once "../includes/head.php" ?>
    <article>
        <img src="../images/miniatures/<?= $article['id'] ?>.jpg" alt="miniature">
        <h2><?= $article['titre'] ?></h2>
        <span class="categorie"><?= getNomCategorie($article['categorie']) ?></span> - <span class="date">
            <?= $article['date_formatee'] ?></span>
        <div class="contenu">
            <?= htmlspecialchars_decode(nl2br($article['contenu'])) ?>
        </div>
    </article>
    <hr>
    <section id="commentaires" class="commentaires">
        <h2>Commentaires</h2>
        <form method="POST" action="#commentaires">
            <input type="text" name="nom" placeholder="Nom" <?php if (isset($nom)) {
                                                                echo ' value="' . $nom . '"';
                                                            } ?>required>

            <input type="email" name="email" placeholder="email" <?php if (isset($email)) {
                                                                        echo ' value="' . $email . '"';
                                                                    } ?>required>
            
            <br>
            <textarea name="contenu" placeholder="Votre commentaire ..." <?php if (isset($contenu)) {
                                                                                echo $contenu;
                                                                            } ?>required>
</textarea>
            <br>
            <input type="submit" name="commentaire" value="Poster le commentaire">




        </form>
        <?php if ($message_commentaire) {
            echo '<p>' . $message_commentaire . '</p>';
        } ?>

<?php while($c = $commentaires->fetch(PDO::FETCH_ASSOC)){?>
        <div class="commentaire">

            <p><?=$c['contenu']?></p>
            <span class="auteur">-<?=$c['nom']?></span>
        </div>
<?php }?>
    </section>
    <?php include_once "../includes/foot.php" ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.0/jquery.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/js/bootstrap.min.js"></script>
</body>

</html>