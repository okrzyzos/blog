</section>

        <section class="sidebar">

<form method="GET" action="index.php">
<input type="text" name="q" placeholder="recherche...">
    <input type="submit"  value="ok">


</form>


<h4>Categories</h4>
<ul>
<?php while($c = $side_categories->fetch(PDO::FETCH_ASSOC)){?>
<li><a href="index.php?categorie=<?=$c['categorie_url']?>"><?= $c['categorie']?></a></li>
<?php }?>



</ul>
<h4>Commentaires récents</h4>
<ul>

<?php while($c = $side_commentaires->fetch(PDO::FETCH_ASSOC)){?>
<li>
<a href="articles.php?id=<?= $c['id_article']?>#commentaires">
<i><?=substr($c['contenu'],0,100)?></i><br>
 - <?=$c['nom']?>
</a>
</li>
<?php }?>
</ul>




        </section>

<div class="clearfix"></div>



    </div>

<footer>
<div class="container">
<p>&copy;Tous droits réservés...</p>
</div>
</footer>