<?php
session_start();


if(isset($_SESSION['admin']) AND $_SESSION['admin']){


    header('Location: administration.php');
 }



$erreur = '';
if(isset($_POST['connexion'])){
    if(isset($_POST['pseudo'] ,$_POST['mdp'])) {

$pseudo = htmlspecialchars($_POST['pseudo']) ;
$mdp = htmlspecialchars($_POST['mdp']) ;

if(!empty($pseudo) AND !empty($mdp)) {


if($pseudo == 'olive' AND $mdp == '1234') {

$_SESSION['admin'] = true;
header('Location: administration.php');


} else {


$erreur = 'les identifiants que vous avez choisi sont invalides';


}
} else {


$erreur = 'Veuillez saisir votre nom d \'utlisateur et votre mot de passe ';


}
} else {


$erreur = 'les identifiants que vous avez choisi sont invalides';


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
    <title>Connexion</title>
</head>

<body>
<?php include_once "../includes/head.php" ?>


           <h2>Connexion</h2>


           <form method="POST">

           <input type="text" placeholder="Nom Utilisateur" name="pseudo" <?php if(isset($pseudo)) { ?> value="<?=$pseudo ?>"<?php }?>>
           <br>
           <input type="password" placeholder="Mot de passe" name="mdp" <?php if(isset($mdp)) { ?> value="<?=$mdp ?>"<?php }?>>
           <br>
           <input type="submit" name="connexion" value="se connecter">

           </form>

           <?php if($erreur) {?>

<p style="color:red;"><?= $erreur ?></p>

           <?php }?>

<?php include_once "../includes/foot.php" ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.0/jquery.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/js/bootstrap.min.js"></script>
</body>

</html>