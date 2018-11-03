<?php
include('config.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="<?php echo $design; ?>/style.css" rel="stylesheet" title="Style" />
        <title>Calendrier</title>
    </head>
    <body>
    	<div class="header">
        	<a href="<?php echo $url_home; ?>"><img src="<?php echo $design; ?>/images/logo.png" alt="Espace Membre" /></a>
	    </div>
        <div class="content">
        <a href="connexion.php" style="float:right;"><img src="default\images\exit.png" style="width:20px;"></a>

<?php
//On verifie si lutilisateur est connecte
if(isset($_SESSION['pseudo']))
{
    $req1 = mysqli_fetch_array(mysqli_query($connexion, "select nb_cours from membre where id=".$_SESSION['id_membre']));
    $req2 = mysqli_fetch_array(mysqli_query($connexion, "select date_cal from calendrier where date_cal >= ".time()." order by date_cal"));
    $req3 = mysqli_fetch_array(mysqli_query($connexion, 'select count(*) as nb_inscrit from inscription where date_cal="'.$req2['date_cal'].'"'));  
    $req4 = mysqli_fetch_array(mysqli_query($connexion, "select nb_place from nombreplace"));
    $req5 = mysqli_fetch_array(mysqli_query($connexion, 'select count(*) as nb_desinscrit from desinscription where date_cal="'.$req2['date_cal'].'"'));
    $req6 = mysqli_fetch_array(mysqli_query($connexion, 'select count(*) as nb_inscrit_30 from membre where nb_cours="30"'));

    $nombre_place_restant = $req4['nb_place'] - $req6['nb_inscrit_30'] + $req5['nb_desinscrit'] - $req3['nb_inscrit'];
    // echo "nombre de place :".$nombre_place_restant;

    if ($_SESSION['id_role']<3)
    {
        echo '<a href="edit_calendrier.php">MAJ du calendrier</a><br />';
        echo '<a href="liste_inscrits.php">Liste des inscrits</a><br />';
    }
    echo '<h1>Inscription pour le ';
    echo date("d/m/Y", $req2['date_cal'])."</h1><br>";
    if ($req1['nb_cours']<30)
    {
        echo 'Il vous reste : '.$req1['nb_cours'].' cours.<br />';
    }
    echo 'Il reste '.$nombre_place_restant.' place(s).<br />';
    if (isset($_GET['message']) and $_GET['message']!="") echo $_GET['message'].'<br />';
              
    
    if ($req1['nb_cours']==30)
    {
        $req7 = mysqli_fetch_array(mysqli_query($connexion, 'select count(*) as nb_desinscrit_perso from desinscription where date_cal="'.$req2['date_cal'].'" and id_membre="'.$_SESSION['id_membre'].'"'));
        if($req7['nb_desinscrit_perso']==0)
        {
            echo 'Vous êtes inscrit automatiquement à ce cours.<br />';
            echo "Voulez-vous vous désinscrire ?<br />";
            ?>
            <form method=POST action=inscription.php >
                <input type=hidden name="date_inscription_cours" value="<?php echo $req2['date_cal'] ?>">
                <input type=hidden name="nb_cours_inscrit" value="<?php echo $req1['nb_cours'] ?>">
                <input type="radio" name="participation" id="non" value="non" checked="checked"/>
                Je ne souhaite pas participer à ce cours <br />
                <input type=submit value=Valider>
            </form>
            <?php
        }
        else
        {
            if($nombre_place_restant>0)
            {
                echo "Voulez-vous vous inscrire ?";
                ?>
                <form method=POST action=inscription.php >
                    <input type=hidden name="date_inscription_cours" value="<?php echo $req2['date_cal'] ?>">
                    <input type=hidden name="nb_cours_inscrit" value="<?php echo $req1['nb_cours'] ?>">
                    <input type="radio" name="participation" id="oui" value="oui" checked="checked"/>
                    Je souhaite participer à ce cours <br />
                    <input type=submit value=Valider>
                </form>
                <?php
            }
            else
            {
                echo 'il n\'y a plus de place';
            }
        }
    }
    else
    {
        $req8 = mysqli_fetch_array(mysqli_query($connexion, 'select count(*) as nb_inscrit_perso from inscription where date_cal="'.$req2['date_cal'].'" and id_membre="'.$_SESSION['id_membre'].'"'));
        if($req8['nb_inscrit_perso']==0)
        {
            if ($req1['nb_cours']>0)
            {
               if($nombre_place_restant>0)
                {
                    echo "Voulez-vous vous inscrire ?";
                    ?>
                    <form method=POST action=inscription.php >
                        <input type=hidden name="date_inscription_cours" value="<?php echo $req2['date_cal'] ?>">
                        <input type=hidden name="nb_cours_inscrit" value="<?php echo $req1['nb_cours'] ?>">
                        <input type="radio" name="participation" id="oui" value="oui" checked="checked"/>
                        Je souhaite participer à ce cours<br />
                        <input type=submit value=Valider>
                    </form>
                    <?php
                }
                else
                {
                    echo 'il n\'y a plus de place';
                }
            }
            else
            {
                echo 'Vous n\'avez plus de cours';
            }
        }
        else
        {
            echo "Voulez-vous vous désinscrire ?";
            ?>
            <form method=POST action=inscription.php >
                <input type=hidden name="date_inscription_cours" value="<?php echo $req2['date_cal'] ?>">
                <input type=hidden name="nb_cours_inscrit" value="<?php echo $req1['nb_cours'] ?>">
                <input type="radio" name="participation" id="non" value="non" checked="checked"/>
                Je ne souhaite pas participer à ce cours <br />
                <input type=submit value=Valider>
            </form>
            <?php
        }
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
		</div>
		<div class="foot"><a href="<?php echo $url_home; ?>">Retour &agrave; l'accueil</a> - Manon Prod</div>
	</body>
</html>