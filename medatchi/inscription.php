<?php
include('config.php');

//On verifie si lutilisateur est connecte
if(isset($_SESSION['pseudo']))
{
	$message = "";
	//On verifie si le formulaire a ete envoye
	if(isset($_POST['participation']))
	{
		$req1 = mysqli_fetch_array(mysqli_query($connexion, 'select count(*) as nb_inscrit from inscription where date_cal="'.$_POST['date_inscription_cours'].'"'));
		$req2 = mysqli_fetch_array(mysqli_query($connexion, 'select count(*) as nb_desinscrit from desinscription where date_cal="'.$_POST['date_inscription_cours'].'"'));
		$req3 = mysqli_fetch_array(mysqli_query($connexion, 'select count(*) as nb_inscrit_30 from membre where nb_cours="30"'));
		$req4 = mysqli_fetch_array(mysqli_query($connexion, 'select nb_place from nombreplace'));
		$nombre_place_restant = $req4['nb_place'] - $req3['nb_inscrit_30'] + $req2['nb_desinscrit'] - $req1['nb_inscrit'];

		if($_POST['nb_cours_inscrit']==30)
		{
			if ($_POST['participation']=="non")
			{
				$req5 = mysqli_fetch_array(mysqli_query($connexion, 'select count(*) as nb_desinscrit_30 from desinscription where date_cal="'.$_POST['date_inscription_cours'].'" and id_membre="'.$_SESSION['id_membre'].'"'));
				
				if($req5['nb_desinscrit_30']==0)
				{
					mysqli_query($connexion, 'insert into desinscription(id_membre, date_cal) values ("'.$_SESSION['id_membre'].'", "'.$_POST['date_inscription_cours'].'")');
				}
				else
				{
					$message = "Vous vous êtes déja desinscrit de ce cours.";
				}
			}
			elseif ($_POST['participation']=="oui")
			{
				if($nombre_place_restant>0)
				{
					mysqli_query($connexion, 'delete from desinscription where id_membre="'.$_SESSION['id_membre'].'" and date_cal="'.$_POST['date_inscription_cours'].'"');
				}
				else
				{
					$message = "Quelqu'un s'est inscrit à ce cours entre temps, il n'y a plus de place.";
				}
			}

		}
		else
		{
			if ($_POST['participation']=="oui")
			{
				
				$dn = mysqli_fetch_array(mysqli_query($connexion, 'select count(*) as nb from inscription where id_membre="'.$_SESSION['id_membre'].'" and date_cal="'.$_POST['date_inscription_cours'].'"'));
				if($dn['nb']==0)
				{
					if($nombre_place_restant>0)
					{
						$nouveau_nb_cours = $_POST['nb_cours_inscrit'] - 1;
						mysqli_query($connexion, 'insert into inscription(id_membre, date_cal) values ("'.$_SESSION['id_membre'].'", "'.$_POST['date_inscription_cours'].'")');
						mysqli_query($connexion, 'update membre set nb_cours="'.$nouveau_nb_cours.'" where id="'.$_SESSION['id_membre'].'"');
					}
					else
					{
						$message = "Quelqu'un s'est inscrit à ce cours entre temps, il n'y a plus de place.";
					}
				}
				else
				{
					$message = "Vous êtes déja inscrit à ce cours.";
				}
				
			}
			elseif ($_POST['participation']=="non")
			{

				$dn = mysqli_fetch_array(mysqli_query($connexion, 'select count(*) as nb from inscription where id_membre="'.$_SESSION['id_membre'].'" and date_cal="'.$_POST['date_inscription_cours'].'"'));
				if($dn['nb']!=0)
				{
					$nouveau_nb_cours = $_POST['nb_cours_inscrit'] + 1;
					mysqli_query($connexion, 'delete from inscription where id_membre="'.$_SESSION['id_membre'].'" and date_cal="'.$_POST['date_inscription_cours'].'"');
					mysqli_query($connexion, 'update membre set nb_cours="'.$nouveau_nb_cours.'" where id="'.$_SESSION['id_membre'].'"');
				}
				else
				{
					$message = "Vous n'êtes pas inscrit à ce cours.";
				}
			}
		}
		
			header('Location: calendrier.php?message='.$message);
			exit();
	}
}