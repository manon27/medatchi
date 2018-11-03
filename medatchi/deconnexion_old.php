<?php
include('config.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="<?php echo $design; ?>/style.css" rel="stylesheet" title="Style" />
        <title>Connection</title>
    </head>
    <body>
    	<div class="header">
        	<a href="<?php echo $url_home; ?>"><img src="<?php echo $design; ?>/images/logo.png" alt="Espace Membre" /></a>
	    </div>
<?php
//Si lutilisateur est connecte, on le deconnecte
if(isset($_SESSION['pseudo']))
{
	//On le deconnecte en supprimant simplement les sessions pseudo et userid
	unset($_SESSION['pseudo'], $_SESSION['id_membre']);
?>
<div class="message">Vous avez bien &eacute;t&eacute; d&eacute;connect&eacute;.<br />
<a href="<?php echo $url_home; ?>">Accueil</a></div>
<?php
}
else
{
	$opseudo = '';
	//On verifie si le formulaire a ete envoye
	if(isset($_POST['pseudo'], $_POST['password']))
	{
		//On echappe les variables pour pouvoir les mettre dans des requetes SQL
		if(get_magic_quotes_gpc())
		{
			$opseudo = stripslashes($_POST['pseudo']);
			$pseudo = mysqli_real_escape_string(stripslashes($_POST['pseudo']));
			$password = stripslashes($_POST['password']);
		}
		else
		{
			$pseudo = mysqli_real_escape_string($connexion, $_POST['pseudo']);
			$password = $_POST['password'];
		}
		//On recupere le mot de passe de lutilisateur
		$req = mysqli_query($connexion, 'select password,id from membre where pseudo="'.$pseudo.'"');
		$dn = mysqli_fetch_array($req);
		//On le compare a celui quil a entre et on verifie si le membre existe
		if($dn['password']==$password and mysqli_num_rows($req)>0)
		{
			//Si le mot de passe est bon, on ne va pas afficher le formulaire
			$form = false;
			//On enregistre son pseudo dans la session pseudo et son identifiant dans la session userid
			$_SESSION['pseudo'] = $_POST['pseudo'];
			$_SESSION['id_membre'] = $dn['id'];
?>
		<?php
		header('Location: calendrier.php');
  		exit();
  		?>
<div class="message">Vous avez bien &eacute;t&eacute; connect&eacute;. Vous pouvez acc&eacute;der &agrave; votre espace membre.<br />
<a href="<?php echo $url_home; ?>">Accueil</a></div>
<?php
		}
		else
		{
			//Sinon, on indique que la combinaison nest pas bonne
			$form = true;
			$message = 'La combinaison que vous avez entr&eacute; n\'est pas bonne.';
		}
	}
	else
	{
		$form = true;
	}
	if($form)
	{
		//On affiche un message sil y a lieu
	if(isset($message))
	{
		echo '<div class="message">'.$message.'</div>';
	}
	//On affiche le formulaire
?>
<div class="content">
    <form action="connexion.php" method="post">
        Veuillez entrer vos identifiants pour vous connecter:<br />
        <div class="center">
            <label for="pseudo">Nom d'utilisateur</label><input type="text" name="pseudo" id="pseudo" value="<?php echo htmlentities($opseudo, ENT_QUOTES, 'UTF-8'); ?>" /><br />
            <label for="password">Mot de passe</label><input type="password" name="password" id="password" /><br />
            <input type="submit" value="Connection" />
		</div>
    </form>
</div>
<?php
	}
}
?>
		<div class="foot"><a href="<?php echo $url_home; ?>">Retour &agrave; l'accueil</a> - <a href="http://www.supportduweb.com/">Support du Web</a></div>
	</body>
</html>