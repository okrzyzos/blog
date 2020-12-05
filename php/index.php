<?php

require_once 'config.php';
require_once 'functions.php';


$info_utilisateur = '';
$aucun_resultat = false;

if(isset($_GET['categorie']) AND empty($_GET['categorie'])) {
	header('Location: /');
	exit();
}

if(!empty($_GET['categorie'])) {

	$get_categorie = htmlspecialchars($_GET['categorie']);
	$nom_categorie = getNomCategorie($get_categorie);

	$info_utilisateur = "Catégorie ".$nom_categorie;

	if(!$nom_categorie) {
		header('Location: /');
		exit();
	}

    $articles = $bdd->prepare('SELECT *, DATE_FORMAT(datetime_post, "%d %M %Y") date_formatee FROM articles WHERE categorie = ? ORDER BY datetime_post DESC');
    $articles->execute([$get_categorie]);
    
    
} elseif(!empty($_GET['q'])) {

	$query = htmlspecialchars($_GET['q']);

	$info_utilisateur = 'Recherche "'.$query.'"';
	
	$articles = $bdd->prepare('SELECT *, DATE_FORMAT(datetime_post, "%d %M %Y") date_formatee FROM articles WHERE titre LIKE ? ORDER BY datetime_post DESC');
	$articles->execute(['%'.$query.'%']);

	if(!$articles->rowCount()) {
		$aucun_resultat = true;
	}

} else {
	$articles = $bdd->query('SELECT *, DATE_FORMAT(datetime_post, "%d %M %Y") date_formatee FROM articles ORDER BY datetime_post DESC');
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
    <title>Blog</title>
</head>

<body>
    <?php include_once "../includes/head.php" ?>
    <?php if($info_utilisateur) { ?>
<h2><?= $info_utilisateur ?></h2>
<?php } ?>

<?php if($aucun_resultat) { ?>
<h3>Aucun résultat ne correspond à votre recherche...</h3>
<?php } ?>



    <?php while ($a = $articles->fetch(PDO::FETCH_ASSOC)) { ?>
        <div class="article">
            <div class="image-wrapper">
                <a href="articles.php?id=<?= $a['id'] ?>">
                    <img src="../images/miniatures/<?= $a['id'] ?>.jpg" alt="<?= $a['titre'] ?>">" alt="<?= $a['titre'] ?>">
                </a>
            </div>
            <h3><a href="articles.php?id=<?= $a['id'] ?>"><?= $a['titre'] ?></a></h3>
            <span class="categorie"><?= getNomCategorie($a['categorie']) ?></span> - <span class="date">
                <?= $a['date_formatee'] ?></span>
                <!-- changer html  contenu et couper  -->
            <p><?= substr(strip_tags(htmlspecialchars_decode($a['contenu'])), 0, 120) . '...' ?></p>
        </div>
    <?php } ?>


    <?php include_once "../includes/foot.php" ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.0/jquery.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/js/bootstrap.min.js"></script>
</body>

</html>