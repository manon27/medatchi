<?php
include('config.php');
		//On verifie si lutilisateur est connecte
		if(isset($_SESSION['pseudo']))
		{
			//On verifie si le formulaire a ete envoye
			if(isset($_POST['pseudo'], $_POST['nom'], $_POST['prenom'], $_POST['email']))
			{
				//On enleve lechappement si get_magic_quotes_gpc est active
				if(get_magic_quotes_gpc())
				{
					$_POST['pseudo'] = stripslashes($_POST['pseudo']);
					$_POST['nom'] = stripslashes($_POST['nom']);
					$_POST['prenom'] = stripslashes($_POST['prenom']);
					$_POST['email'] = stripslashes($_POST['email']);
				}
				//On verifie si lemail est valide
				if(preg_match('#^(([a-z0-9!\#$%&\\\'*+/=?^_`{|}~-]+\.?)*[a-z0-9!\#$%&\\\'*+/=?^_`{|}~-]+)@(([a-z0-9-_]+\.?)*[a-z0-9-_]+)\.[a-z]{2,}$#i',$_POST['email']))
				{
					//On echape les variables pour pouvoir les mettre dans une requette SQL
					$pseudo = mysqli_real_escape_string($connexion, $_POST['pseudo']);
					$nom = mysqli_real_escape_string($connexion, $_POST['nom']);
					$prenom = mysqli_real_escape_string($connexion, $_POST['prenom']);
					$email = mysqli_real_escape_string($connexion, $_POST['email']);
					//On verifie sil ny a pas deja un utilisateur inscrit avec le pseudo choisis
					$dn = mysqli_fetch_array(mysqli_query($connexion, 'select count(*) as nb from membre where pseudo="'.$pseudo.'"'));
					//On verifie si le pseudo a ete modifie pour un autre et que celui-ci n'est pas deja utilise
					if($dn['nb']==0 or $_POST['pseudo']==$_SESSION['pseudo'])
					{
						//On modifie les informations de lutilisateur avec les nouvelles
						if(mysqli_query($connexion, 'update membre set pseudo="'.$pseudo.'", nom="'.$nom.'", prenom="'.$prenom.'", email="'.$email.'" where id="'.mysqli_real_escape_string($connexion, $_SESSION['id_membre']).'"'))
						{
							//Si ca a fonctionne, on naffiche pas le formulaire
							$form = false;
							//On supprime les sessions pseudo au cas ou il aurait modifie son pseudo
							unset($_SESSION['pseudo']);
							$_SESSION['pseudo']=$pseudo;
							header('Location: index.php');
							// header('refresh:1;url=index.php');
							exit();
						}
						else
						{
							//Sinon on dit quil y a eu une erreur
							$form = true;
							$message = 'Une erreur est survenue lors des modifications.';
						}		
					}
					else
					{
						//Sinon, on dit que le pseudo voulu est deja pris
						$form = true;
						$message = 'Un autre utilisateur utilise d&eacute;j&agrave; le nom d\'utilisateur que vous d&eacute;sirez utiliser.';
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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="<?php echo $design; ?>/style.css" rel="stylesheet" title="Style" />
        <title>Modifier ses informations personnelles</title>
    </head>
    <body>
    	<div class="header">
        	<a href="<?php echo $url_home; ?>"><img src="<?php echo $design; ?>/images/logo.png" alt="Espace Membre" /></a>
	    </div>
<?php

			if($form)
			{
				//On affiche un message sil y a lieu
				if(isset($message))
				{
					echo '<strong>'.$message.'</strong>';
				}
				//Si le formulaire a deja ete envoye on recupere les donnes que lutilisateur avait deja insere
				if(isset($_POST['pseudo'], $_POST['nom'], $_POST['prenom'], $_POST['email']))
				{
					$pseudo = htmlentities($_POST['pseudo'], ENT_QUOTES, 'UTF-8');
					$nom = htmlentities($_POST['nom'], ENT_QUOTES, 'UTF-8');
					$prenom = htmlentities($_POST['prenom'], ENT_QUOTES, 'UTF-8');
					$email = htmlentities($_POST['email'], ENT_QUOTES, 'UTF-8');
				}
				else
				{
					//Sinon, on affiche les donnes a partir de la base de donnee
					$dnn = mysqli_fetch_array(mysqli_query($connexion, 'select pseudo,nom,prenom,email from membre where id="'.$_SESSION['id_membre'].'"'));
					$pseudo = htmlentities($dnn['pseudo'], ENT_QUOTES, 'UTF-8');
					$nom = htmlentities($dnn['nom'], ENT_QUOTES, 'UTF-8');
					$prenom = htmlentities($dnn['prenom'], ENT_QUOTES, 'UTF-8');
					$email = htmlentities($dnn['email'], ENT_QUOTES, 'UTF-8');
				}
				//On affiche le formulaire
			?>
			<div class="content">
			<a href="connexion.php" style="float:right;"><img src="default\images\exit.png" style="width:20px;"></a>
			<br />
			    <form action="edit_infos.php" method="post">
			        Vous pouvez modifier vos informations:<br />
			        <div class="center">
			            <label for="pseudo">Pseudo</label><input type="text" name="pseudo" id="pseudo" value="<?php echo $pseudo; ?>" /><br />
			            <label for="nom">Nom</label><input type="text" name="nom" id="nom" value="<?php echo $nom; ?>" /><br />
			            <label for="prenom">Pr&eacute;nom</label><input type="text" name="prenom" id="prenom" value="<?php echo $prenom; ?>" /><br />
			            <label for="email">Email</label><input type="email" name="email" id="email" value="<?php echo $email; ?>" /><br />
			            <br />
			            <input type="submit" value="Envoyer" />
			        </div>
			    </form>
			</div>
		<?php
			}
		}
		else
		{
			?>
			<div class="message">Pour acc&eacute;der &agrave; cette page, vous devez &ecirc;tre connect&eacute;.<br />
			<a href="connexion.php">Se connecter</a></div>
			<?php
		}
		?>
				<div class="foot"><a href="<?php echo $url_home; ?>">Retour &agrave; l'accueil</a> - Manon Prod</div>
	</body>
</html>