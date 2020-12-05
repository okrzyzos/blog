<?php
 session_start();
 require_once 'config.php';

 if(!isset($_SESSION['admin']) OR !$_SESSION['admin']){


    header('Location: index.php');
 }
$message_categorie = '';
// if(isset($_POST['categorie'])){
if(!empty($_POST['categorie']) AND !empty($_POST['categorie_url'])){
    $categorie= htmlspecialchars($_POST['categorie']);
    $categorie_url= htmlspecialchars($_POST['categorie_url']);
    $insert = "INSERT INTO categories (categorie,categorie_url) VALUES (:categorie,:categorie_url)";
    $req = $bdd->prepare($insert);
    $req->bindParam(':categorie',$categorie);
    $req->bindParam(':categorie_url',$categorie_url);
   $result=  $req->execute();



//     $insert = "INSERT INTO utilisateurs (name, firstname, adress, email, date_birthday, password) VALUES (:name, :firstname, :adress, :email, :date_birthday, :password)";
//     $stmt = $bdd->prepare($insert);
//     $stmt->execute(['email' => $email, 'firstname' => $firstname, 'name' => $name, 'adress' => $adress, 'date_birthday' => $date_birthday, ':password' => $password]);
 if($result){
     $message =" la nouvelle categorie a bien  ete  ajoutee";

}else{
 $message = " une erreur est survenue ";
}

}else{
 $message_categorie = 'Veuillez renseigner un slug ainsi qu \' une categorie';

 }


$categories = $bdd->query('SELECT * FROM categories');

$message_article='';
$taille_maximum = 8;
if(isset($_POST['article'])){

if(isset($_POST['categorie_article'], $_POST['titre'],$_POST['contenu'],$_FILES['miniature']['tmp_name'] )){

$categorie = htmlspecialchars($_POST['categorie_article']);
$titre = htmlspecialchars($_POST['titre']);
$contenu = htmlspecialchars($_POST['contenu']);
$miniature = $_FILES['miniature'];

if(!empty($categorie) AND !empty($titre) AND !empty($contenu) AND !empty($miniature)){

if(filesize($miniature['tmp_name']) <= $taille_maximum*1000000){

if(exif_imagetype($miniature['tmp_name']) == 2){

$ins = $bdd->prepare('INSERT INTO articles(titre,categorie,contenu,datetime_post) VALUES (:titre,:categorie,:contenu,NOW())');
$res = $ins->execute([
    ':titre' => $titre,
    ':categorie' => $categorie,
    ':contenu' => $contenu
]);

if($res){

    $last_id = $bdd->lastInsertId();
$chemin = '../images/miniatures/'.$last_id.'.jpg';
$move = move_uploaded_file($miniature['tmp_name'], $chemin);
if($move){

    $message_article = 'votre article a ete creé';

 
} else {

    $message_article = 'une erreur est survenue durant le transfert';

}

} else {

    $message_article = 'une erreur est survenue durant l ajout';
    
    }
} else {

$message_article = 'Votre miniature doit etre au format jpg';

}


} else {

$message_article = 'Votre miniature ne peut pas  depasser' .$taille_maximum.'Mo';

}

}

} else {
$message_article = 'Veuillez completer tous les champs';

}

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
    <title>Administration</title>
</head>

<body>
<?php include_once "../includes/head.php" ?>


           <h2>Administration</h2>
           <a href="deconnexion.php">Se déconnecter</a>

           <br><br>

          <h3>Nouvelles categories</h3> 

          <form method="POST">
<input type="text" name="categorie" placeholder="Nom categories" required>
<input type="text" name="categorie_url" size="30" placeholder="Slug de la categorie(dans url)"required>
<input type="submit" name="button" value="créer la categorie">



          </form>
       <?php if($message_categorie) {echo '<p>'.$message_categorie.'</p>';}?>
<h3>Rediger un Article</h3>

<form method="POST" enctype="multipart/form-data">
<select name="categorie_article" required>
<?php while($o = $categories->fetch(PDO::FETCH_ASSOC)){ ?>

<option value="<?= $o['categorie_url']?>" <?php if(isset($categorie) AND $categorie == $o['categorie_url']){echo 'selected';} ?>><?= $o['categorie']?></option>


<?php }?>

</select>
<br>
<input type="text" name="titre" placeholder="titre de l 'article"<?php if(isset($titre)) { echo 'value="'.$titre.'"'; } ?> required>
<br>
<textarea name="contenu" placeholder="contenu de l 'article" style="width:80%;" required><?php if(isset($contenu)) { echo $contenu; } ?></textarea>
<br>
<input type="file" name="miniature" id="miniature"><label for="Miniature" required> Miniature de l 'article</label> 
<br>
<input type="submit" value="publier l 'article" name="article">

</form>
<?php if($message_article) {echo '<p>'.$message_article.'</p>';}?>




<?php include_once "../includes/foot.php" ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.0/jquery.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/js/bootstrap.min.js"></script>
</body>

</html>