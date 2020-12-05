
<?php
$servername = 'localhost';
$dbname = 'Blog';
$inscris = 'root';
try {
    $bdd = new PDO('mysql:host=localhost;dbname=Blog', $inscris);
// echo 'vous etes bien connecte';
$bdd->query('SET lc_time_names = "fr_FR"');
} catch (PDOException $e)

{
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}

$side_categories = $bdd->query('SELECT * FROM categories ORDER BY categorie');
$side_commentaires = $bdd->query('SELECT * FROM commentaires ORDER BY id DESC LIMIT 5');

?>

