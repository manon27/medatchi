<?php
include('config.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="<?php echo $design; ?>/style.css" rel="stylesheet" title="Style" />
        <title>Liste inscrits</title>
    </head>
    <body>
    	<div class="header">
        	<a href="<?php echo $url_home; ?>"><img src="<?php echo $design; ?>/images/logo.png" alt="Espace Membre" /></a>
	    </div>
        <div class="content">
        <a href="connexion.php" style="float:right;"><img src="default\images\exit.png" style="width:20px;"></a>

<?php
//On verifie si lutilisateur est connecte
if(isset($_SESSION['pseudo']) and ($_SESSION['id_role']<3))
{
    $req1 = mysqli_fetch_array(mysqli_query($connexion, "select date_cal from calendrier where date_cal >= ".time()." order by date_cal"));
    $req2 = mysqli_query($connexion, 'select id_membre from inscription where date_cal = "'.$req1['date_cal'].'"');
    $req3 = mysqli_fetch_array(mysqli_query($connexion, 'select count(*) as nb_inscrit_15 from inscription where date_cal="'.$req1['date_cal'].'"'));
    $req5 = mysqli_query($connexion, 'select id, pseudo, nom, prenom, email from membre where nb_cours = 30');
?>
        Voici la liste des inscrits:
        <table>
            <tr>
                <th>Pseudo</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
            </tr>
        <?php
        //On recupere les identifiants, les pseudos, les noms, les prenoms et les emails des utilisateurs
        
        
        while($dn2 = mysqli_fetch_array($req5))
        {
            $req6 = mysqli_fetch_array(mysqli_query($connexion, 'select count(*) as desinscrit_30 from desinscription where date_cal = "'.$req1['date_cal'].'" and id_membre="'.$dn2['id'].'"'));
            if($req6['desinscrit_30']==0)
            {
                ?>
                <tr>
                    <td class="left"><?php echo htmlentities($dn2['pseudo'], ENT_QUOTES, 'UTF-8'); ?></a></td>
                    <td class="left"><?php echo $dn2['nom']; ?></td>
                    <td class="left"><?php echo $dn2['prenom']; ?></td>
                    <td class="left"><?php echo htmlentities($dn2['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
                <?php
            }
        }
        if($req3['nb_inscrit_15']>0)
        {
            while($dnn = mysqli_fetch_array($req2))
            {
                $req4 = mysqli_fetch_array(mysqli_query($connexion, 'select pseudo, nom, prenom, email from membre where id = '.$dnn['id_membre']));
                ?>
                <tr>
                    <td class="left"><?php echo htmlentities($req4['pseudo'], ENT_QUOTES, 'UTF-8'); ?></a></td>
                    <td class="left"><?php echo $req4['nom']; ?></td>
                    <td class="left"><?php echo $req4['prenom']; ?></td>
                    <td class="left"><?php echo htmlentities($req4['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
                <?php
            }
        }
        ?>
        </table>
        <?php
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