<?php
include('config.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="<?php echo $design; ?>/style.css" rel="stylesheet" title="Style" />
        <title>Inscription</title>
    </head>
    <body>
    	<div class="header">
        	<a href="<?php echo $url_home; ?>"><img src="<?php echo $design; ?>/images/logo.png" alt="Espace Membre" /></a>
	    </div>
<?php
//On verifie que le formulaire a ete envoye
if(isset($_POST['pseudo'], $_POST['email']) and $_POST['pseudo']!='')
{
	//On enleve lechappement si get_magic_quotes_gpc est active
	if(get_magic_quotes_gpc())
	{
		$_POST['nom'] = stripslashes($_POST['nom']);
		$_POST['prenom'] = stripslashes($_POST['prenom']);
		$_POST['pseudo'] = stripslashes($_POST['pseudo']);
		$_POST['email'] = stripslashes($_POST['email']);
	}
	//On verifie si lemail est valide
	if(preg_match('#^(([a-z0-9!\#$%&\\\'*+/=?^_`{|}~-]+\.?)*[a-z0-9!\#$%&\\\'*+/=?^_`{|}~-]+)@(([a-z0-9-_]+\.?)*[a-z0-9-_]+)\.[a-z]{2,}$#i',$_POST['email']))
	{
		//On echape les variables pour pouvoir les mettre dans une requete SQL
		$nom = mysqli_real_escape_string($connexion, $_POST['nom']);
		$prenom = mysqli_real_escape_string($connexion, $_POST['prenom']);
		$pseudo = mysqli_real_escape_string($connexion, $_POST['pseudo']);
		$email = mysqli_real_escape_string($connexion, $_POST['email']);
		//On verifie sil ny a pas deja un utilisateur inscrit avec le pseudo choisis
		$dn = mysqli_num_rows(mysqli_query($connexion, 'select id from membre where pseudo="'.$pseudo.'"'));
		if($dn==0)
		{
			//On recupere le nombre dutilisateurs pour donner un identifiant a lutilisateur actuel
			$dn2 = mysqli_num_rows(mysqli_query($connexion, 'select id from membre'));
			$id = $dn2+1;
			//On enregistre les informations dans la base de donnee
			if(mysqli_query($connexion, 'insert into membre(pseudo, nom, prenom, email, nb_cours, date_inscription, id_role) values ("'.$pseudo.'", "'.$nom.'", "'.$prenom.'", "'.$email.'", 0, "'.time().'", 3)'))
			{
				//Si ca a fonctionne, on n affiche pas le formulaire
				$form = false;
?>
<div class="message">Vous avez bien &eacute;t&eacute; inscrit. Vous pouvez dor&eacute;navant vous connecter.<br />
<a href="connexion.php">Se connecter</a></div>
<?php
			}
			else
			{
				//Sinon on dit quil y a eu une erreur

				$form = true;
				$message = 'Une erreur est survenue lors de l\'inscription.';
			}
		}
		else
		{
			//Sinon, on dit que le pseudo voulu est deja pris
			$form = true;
			$message = 'Un autre membre utilise d&eacute;j&agrave; le pseudo que vous d&eacute;sirez utiliser.';
		}
	}
	else
	{
		//Sinon, on dit que lemail nest pas valide
		$form = true;
		$message = 'L\'email que vous avez entr&eacute; n\'est pas valide.';
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
		echo '<div class="message">'.$pseudo.', "'.$nom.'", "'.$prenom.'", "'.$email.'", "'.time().'", 3)</div>';	
		echo '<div class="message">insert into membre(pseudo, nom, prenom, email, date_inscription, id_role) values ("'.$pseudo.'", "'.$nom.'", "'.$prenom.'", "'.$email.'", "'.time().'", 3)"</div>';
	}
	//On affiche le formulaire
?>
<div class="content">
    <form action="sign_up.php" method="post">
        Veuillez remplir ce formulaire pour vous inscrire:<br />
        <div class="center">
        	<label for="nom">Nom</label><input type="text" name="nom" value="<?php if(isset($_POST['nom'])){echo htmlentities($_POST['nom'], ENT_QUOTES, 'UTF-8');} ?>" /><br />
        	<label for="prenom">Pr&eacute;nom</label><input type="text" name="prenom" value="<?php if(isset($_POST['prenom'])){echo htmlentities($_POST['prenom'], ENT_QUOTES, 'UTF-8');} ?>" /><br />
            <label for="pseudo">Pseudo</label><input type="text" name="pseudo" value="<?php if(isset($_POST['pseudo'])){echo htmlentities($_POST['pseudo'], ENT_QUOTES, 'UTF-8');} ?>" /><br />
            <label for="email">Email</label><input type="email" name="email" value="<?php if(isset($_POST['email'])){echo htmlentities($_POST['email'], ENT_QUOTES, 'UTF-8');} ?>" /><br />
            <input type="submit" value="Envoyer" />
		</div>
    </form>
</div>
<?php
}
?>
		<div class="foot"><a href="<?php echo $url_home; ?>">Retour &agrave; l'accueil</a> - Manon Prod</div>
	</body>
</html>