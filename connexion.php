<?php
include('config.php');

//Si lutilisateur est connecte, on le deconnecte
if(isset($_SESSION['pseudo']))
{
	//On le deconnecte en supprimant simplement les sessions pseudo et userid
	unset($_SESSION['pseudo'], $_SESSION['id_membre'], $_SESSION['id_role']);
?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="<?php echo $design; ?>/style.css" rel="stylesheet" title="Style" />
        <title>Connexion</title>
    </head>
    <body>
    	<div class="header">
        	<a href="<?php echo $url_home; ?>"><img src="<?php echo $design; ?>/images/logo.png" alt="Espace Membre" /></a>
	    </div>
	<div class="message">Vous avez bien &eacute;t&eacute; d&eacute;connect&eacute;.<br />
	<a href="<?php echo $url_home; ?>">Accueil</a></div>
			<div class="foot"><a href="<?php echo $url_home; ?>">Retour &agrave; l'accueil</a> - <a href="http://www.supportduweb.com/">Support du Web</a></div>
	</body>
</html>
<?php
}
else
{
	$opseudo = '';
	//On verifie si le formulaire a ete envoye
	if(isset($_POST['pseudo'], $_POST['email']))
	{
		//On echappe les variables pour pouvoir les mettre dans des requetes SQL
		if(get_magic_quotes_gpc())
		{
			$opseudo = stripslashes($_POST['pseudo']);
			$pseudo = mysqli_real_escape_string(stripslashes($_POST['pseudo']));
			$email = stripslashes($_POST['email']);
		}
		else
		{
			$pseudo = mysqli_real_escape_string($connexion, $_POST['pseudo']);
			$email = $_POST['email'];
		}
		//On recupere l'email de lutilisateur
		$req = mysqli_query($connexion, 'select pseudo, email, id, id_role from membre where pseudo="'.$pseudo.'" and  email="'.$email.'"');
		$dn = mysqli_fetch_array($req);
		//On le compare a celui quil a entre et on verifie si le membre existe
		if(mysqli_num_rows($req)>0)
		{
			$form = false;
			$_SESSION['pseudo'] = $dn['pseudo'];
			$_SESSION['id_membre'] = $dn['id'];
			$_SESSION['id_role'] = $dn['id_role'];
			if ($_SESSION['id_role']==3)
			{
				header('Location: calendrier.php');
				exit();
			}
			

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="<?php echo $design; ?>/style.css" rel="stylesheet" title="Style" />
        <title>Connexion</title>
    </head>
    <body>
    	<div class="header">
        	<a href="<?php echo $url_home; ?>"><img src="<?php echo $design; ?>/images/logo.png" alt="Espace Membre" /></a>
	    </div>
<div class="message">Vous avez bien &eacute;t&eacute; connect&eacute;. Vous pouvez acc&eacute;der &agrave; votre espace membre.<br />
<a href="<?php echo $url_home; ?>">Accueil</a></div>
<?php
		}
		else
		{
			?>
			<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="<?php echo $design; ?>/style.css" rel="stylesheet" title="Style" />
        <title>Connexion</title>
    </head>
    <body>
    	<div class="header">
        	<a href="<?php echo $url_home; ?>"><img src="<?php echo $design; ?>/images/logo.png" alt="Espace Membre" /></a>
	    </div>
	    <?php
			//Sinon, on indique que la combinaison nest pas bonne
			$form = true;
			$message = 'La combinaison que vous avez entr&eacute; n\'est pas bonne.';
		}
	}
	else
	{
		?>
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="<?php echo $design; ?>/style.css" rel="stylesheet" title="Style" />
        <title>Connexion</title>
    </head>
    <body>
    	<div class="header">
        	<a href="<?php echo $url_home; ?>"><img src="<?php echo $design; ?>/images/logo.png" alt="Espace Membre" /></a>
	    </div>
		<?php
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
            <label for="pseudo">Pseudo</label><input type="text" name="pseudo" id="pseudo" value="<?php echo htmlentities($opseudo, ENT_QUOTES, 'UTF-8'); ?>" /><br />
            <label for="email">Email</label><input type="email" name="email" id="email" /><br />
            <input type="submit" value="Connexion" />
		</div>
    </form>

</div>
<?php
	}
	?>
	<div class="foot"><a href="<?php echo $url_home; ?>">Retour &agrave; l'accueil</a> - Manon Prod</div>
	</body>
</html>
<?php
}
?>
