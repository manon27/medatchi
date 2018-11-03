<?php
include('config.php')
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="<?php echo $design; ?>/style.css" rel="stylesheet" title="Style" />
        <title>Espace membre</title>
    </head>
    <body>
        <div class="header">
            <a href="<?php echo $url_home; ?>"><img src="<?php echo $design; ?>/images/logo.png" alt="Espace Membre" /></a>
        </div>
        <div class="content">
<a href="connexion.php" style="float:right;"><img src="default\images\exit.png" style="width:20px;"></a>

<p>Bonjour <?php if(isset($_SESSION['pseudo'])){echo ' '.htmlentities($_SESSION['pseudo'], ENT_QUOTES, 'UTF-8');} ?>,<br /></p>
<h1>Bienvenue sur notre site.</h1>
<ul>
<?php
if (isset($_SESSION['id_role']) && $_SESSION['id_role']<3)
{
    echo '<li><a href="membre.php">Voir la liste des membres</a></li>';
}
//Si lutilisateur est connecte, on lui donne un lien pour modifier ses informations, pour voir ses messages et un pour se deconnecter
if(isset($_SESSION['pseudo']))
{
?>
<li><a href="calendrier.php">Voir le calendrier</a></li>
<li><a href="edit_infos.php">Modifier mes informations personnelles</a></li>
</ul>
<?php
}
else
{
//Sinon, on lui donne un lien pour sinscrire et un autre pour se connecter
?>
<a href="sign_up.php">Inscription</a><br />
<a href="connexion.php">Se connecter</a>
<?php
}
?>
        </div>
        <div class="foot">Manon Prod</div>
    </body>
</html>