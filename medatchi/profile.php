<?php
include('config.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="<?php echo $design; ?>/style.css" rel="stylesheet" title="Style" />
        <title>Profil d'un utilisateur</title>
    </head>
    <body>
    	<div class="header">
        	<a href="<?php echo $url_home; ?>"><img src="<?php echo $design; ?>/images/logo.png" alt="Espace Membre" /></a>
	    </div>
        <div class="content">
        <a href="connexion.php" style="float:right;"><img src="default\images\exit.png" style="width:20px;"></a>

<?php
//On verifie que lidentifiant de lutilisateur est defini
if(isset($_GET['id']) and $_SESSION['id_role']<3)
{
	$id = intval($_GET['id']);
	//On verifie que lutilisateur existe
	if ($dn = mysqli_query($connexion, 'select pseudo, nom, prenom, email, nb_cours, date_inscription, id_role from membre where id='.$id))
	{
		$dnn = mysqli_fetch_array($dn);
		$pseudo = htmlentities($dnn['pseudo'], ENT_QUOTES, 'UTF-8');
		$nom = htmlentities($dnn['nom'], ENT_QUOTES, 'UTF-8');
		$prenom = htmlentities($dnn['prenom'], ENT_QUOTES, 'UTF-8');
		$email = htmlentities($dnn['email'], ENT_QUOTES, 'UTF-8');
		$nb_cours = htmlentities($dnn['nb_cours'], ENT_QUOTES, 'UTF-8');
		$id_role = htmlentities($dnn['id_role'], ENT_QUOTES, 'UTF-8');
		
		//Si le formulaire a deja ete envoye on recupere les donnes que lutilisateur avait deja insere
		if(isset($_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['nb_cours'], $_POST['id_role']))
		{
			$nom = htmlentities($_POST['nom'], ENT_QUOTES, 'UTF-8');
			$prenom = htmlentities($_POST['prenom'], ENT_QUOTES, 'UTF-8');
			$email = htmlentities($_POST['email'], ENT_QUOTES, 'UTF-8');
			$nb_cours = htmlentities($_POST['nb_cours'], ENT_QUOTES, 'UTF-8');
			$id_role = htmlentities($_POST['id_role'], ENT_QUOTES, 'UTF-8');
		}


?>
		Voici le profil de "<?php echo htmlentities($dnn['pseudo']); ?>" :
		<table style="width:500px;">
			<tr>
		    	<td class="left">
		    		<h1><?php echo $pseudo; ?></h1>
		    		Nom: <?php echo $nom; ?><br />
		    		Pr&eacute;nom: <?php echo $prenom; ?><br />
		    		Email: <?php echo $email; ?><br />
		        	Ce membre s'est inscrit le <?php echo date('d/m/Y',$dnn['date_inscription']);?><br />
		        	Il lui reste <?php echo $nb_cours; ?> cours.<br />
		        </td>
		    </tr>
		</table>
<?php
			
		//On verifie si le formulaire a ete envoye
		if(isset($_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['nb_cours']))
		{
			//On enleve lechappement si get_magic_quotes_gpc est active
			if(get_magic_quotes_gpc())
			{
				$_POST['nom'] = stripslashes($_POST['nom']);
				$_POST['prenom'] = stripslashes($_POST['prenom']);
				$_POST['email'] = stripslashes($_POST['email']);
				$_POST['nb_cours'] = stripslashes($_POST['nb_cours']);
				$_POST['id_role'] = stripslashes($_POST['id_role']);
			}

			//On verifie si lemail est valide
			if(preg_match('#^(([a-z0-9!\#$%&\\\'*+/=?^_`{|}~-]+\.?)*[a-z0-9!\#$%&\\\'*+/=?^_`{|}~-]+)@(([a-z0-9-_]+\.?)*[a-z0-9-_]+)\.[a-z]{2,}$#i',$_POST['email']))
			{
				//On echape les variables pour pouvoir les mettre dans une requete SQL
				$Pnom = mysqli_real_escape_string($connexion, $_POST['nom']);
				$Pprenom = mysqli_real_escape_string($connexion, $_POST['prenom']);
				$Pemail = mysqli_real_escape_string($connexion, $_POST['email']);
				$Pnb_cours = mysqli_real_escape_string($connexion, $_POST['nb_cours']);
				$Pid_role = mysqli_real_escape_string($connexion, $_POST['id_role']);
				
				//On modifie les informations de lutilisateur avec les nouvelles
				if(mysqli_query($connexion, 'update membre set nom="'.$Pnom.'", prenom="'.$Pprenom.'", email="'.$Pemail.'", nb_cours="'.$Pnb_cours.'", id_role="'.$Pid_role.'" where id="'.$id.'"'))
				{
					//Si ca a fonctionne, on naffiche pas le formulaire
					$form = false;
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
				echo '<strong>'.$message.'</strong>';
			}
		}
		$req2 = mysqli_query($connexion, 'SELECT id, titre FROM role');
	  	
		echo 'Modifier ces informations';
		?>
	    <form action="profile.php?id=<?php echo $id; ?>" method="post">
	        Vous pouvez modifier les informations suivantes du membre: <?php echo htmlentities($dnn['pseudo'], ENT_QUOTES, 'UTF-8'); ?><br />
	        <div class="center">
	            <label for="nom">Nom</label><input type="text" name="nom" id="nom" value="<?php echo $nom; ?>" /><br />
	            <label for="prenom">Pr&eacute;nom</label><input type="text" name="prenom" id="prenom" value="<?php echo $prenom; ?>" /><br />
	            <label for="email">Email</label><input type="email" name="email" id="email" value="<?php echo $email; ?>" /><br />
	            <label for="nb_cours">Nombre de cours</label><input type="text" name="nb_cours" id="nb_cours" value="<?php echo $nb_cours; ?>" /><br />
	            <label for="id_role">Role</label>
	            <select name="id_role">
	            	 <?php    
					while($dnn2 = mysqli_fetch_array($req2))
					{
						$sel="";
						if ($id_role==$dnn2['id'])
						{
							$sel=" selected";
						}
						echo "<option value=".$dnn2['id']." ".$sel.">".$dnn2['titre']."</option>";
					}
					?>
	            </select>

	            <!-- <input type="text" name="id_role" id="id_role" value="<?php// echo $id_role; ?>" /><br /> -->
	         <br />
	            <input type="submit" value="Envoyer" />
	        </div>
	    </form>
	    <?php
	
	}
	else
	{
		echo 'Ce membre n\'existe pas.';
	}
}
else
{
	echo 'L\'identifiant de l\'utilisateur n\'est pas d&eacute;fini.';
}
?>
		</div>
		<div class="foot"><a href="membre.php">Retour &agrave; la liste des membres</a> - Manon Prod</div>
	</body>
</html>