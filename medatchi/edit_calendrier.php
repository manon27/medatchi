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
    if ($_SESSION['id_role']<3)
    {
        $anneeModifd = date('Y',time());
        $anneeModiff = date('Y',time())+1;
        if(isset($_POST['anneeModifd']) and isset($_POST['anneeModiff']))
        {
            $anneeModifd = $_POST['anneeModifd'];
            $anneeModiff = $_POST['anneeModiff'];
        }

?>
        <form action="edit_calendrier.php" method="post"><br />
            <div class="center">
                <label for="anneeModifd" style="width:400px">Indiquer le début d'année de la saison à modifier :</label>
                <input type="number" name="anneeModifd" id="anneeModifd" value="<?php echo $anneeModifd; ?>" />
                <br />
                <label for="anneeModiff" style="width:400px">Indiquer la fin d'année de la saison à modifier :</label>
                <input type="number" name="anneeModiff" id="anneeModiff" value="<?php echo $anneeModiff; ?>" />
                <br />
                <input type="submit" value="Valider"/>
            </div>
        </form>
<?php
        $max = 0;
        if (isset($_POST['max'])) {
            $max = $_POST['max'];
        }
        for ($l=0;$l<$max;$l++) {
            if (isset($_POST['suppDate_'.$l])) 
            {
                mysqli_query($connexion, 'delete from calendrier where id='.$_POST['suppDate_'.$l]);
            }
            if (isset($_POST['modifDateC_'.$l])) 
            {
                $timestamp = date_create_from_format('Y-m-d', $_POST['modifDate_'.$l])->getTimestamp();
                mysqli_query($connexion, 'update calendrier set date_cal="'.$timestamp.'" where id='.$_POST['modifDateC_'.$l]);
            }
        }
        if (isset($_POST['ajoutDate']) and ($_POST['ajoutDate']!='')) {
            $nouvelleDate = date_create_from_format('Y-m-d', $_POST['ajoutDate'])->getTimestamp();
            mysqli_query($connexion, 'insert into calendrier(date_cal) values ("'.$nouvelleDate.'")');
        }

        $debut=mktime(23,59,0,8,1,date('Y',time()));
        $fin=mktime(23,59,0,7,31,date('Y',time())+1);

        if(isset($_POST['anneeModifd']) and isset($_POST['anneeModiff']))
        {
            $debut=mktime(0,0,0,8,1,$_POST['anneeModifd']);
            $fin=mktime(0,0,0,7,31,$_POST['anneeModiff']);
        }

        $req1 = mysqli_query($connexion, 'select id, date_cal from calendrier where date_cal >= '.$debut.' and  date_cal <= '.$fin.' order by date_cal');
        echo '<form action="edit_calendrier.php" method="post"><table>';
        echo '<tr><th>date</th><th>Modification</th><th>Suppression</th></tr>';
        $k = 0;
        while($dnn = mysqli_fetch_array($req1))
        {
            echo '<tr>';
            echo '<td>'.date("d/m/Y", $dnn['date_cal'])."</td>";
            echo '<td><input type="date" name="modifDate_'.$k.'" id="'.$dnn['id'].'" value="'.date("Y-m-d", $dnn['date_cal']).'"/><input type="checkbox" name="modifDateC_'.$k.'" value="'.$dnn['id'].'" id="'.$dnn['id'].'"/></td>';
            echo '<td><input type="checkbox" name="suppDate_'.$k.'" value="'.$dnn['id'].'" id="'.$dnn['id'].'"/> </td>';
            
            echo '</tr>';
            $k++;
        }
        echo '</table>';
        echo '<input type="hidden" name="max" value="'.$k.'">';
        echo '<label for="ajoutDate">Ajouter une date :</label>
                <input type="date" name="ajoutDate" id="ajoutDate" /><br />';
        echo '<input type="submit" value="Valider"/></form>';

        if(isset($_POST['anneed']) and isset($_POST['anneef']))
        {
            echo "Oui";
            $i=mktime(23,59,0,8,1,$_POST['anneed']);
            $j=mktime(23,59,0,7,31,$_POST['anneef']);
            $req2 = mysqli_fetch_array(mysqli_query($connexion, 'select count(*) as date_insc from calendrier where date_cal > '.$i));
            if ($req2['date_insc']>0)
            {
                echo "Le calendrier a déjà été intégré.";
            }
            else
            {
                $pas=60*60*24;
                $fin=$i+(60*60*24*6);
                //recherche du premier jour choisi de la période donnée
                //si on tombe sur le bon, on sort de la boucle
                for($deb=$i; $deb<= $fin; $deb+=$pas)
                {
                    //if(date("N", $deb)==$_POST['jour_semaine'])
                    if(date("N", $deb)==3)
                    {
                        $premier=$deb;
                        break;
                    }
                }
                //ici, on a un pas de 7 jours, histoire de tomber tout le temps sur le même jour de la semaine.
                //par exemple, on sort tous les mercredis de la période choisie.
                $pas=60*60*24*7;
                //récupération de tous les jours choisis pour la période donnée
                for($premier; $premier <= $j; $premier+=$pas)
                {
                    mysqli_query($connexion, 'insert into calendrier(date_cal) values ("'.$premier.'")');
                }
            }
        }
?>
        <form action="edit_calendrier.php" method="post"><br />
            <div class="center">
                <input type=hidden name="anneed" value="<?php echo date('Y',time()); ?>">
                <input type=hidden name="anneef" value="<?php echo date('Y',time())+1; ?>">
                <input type="submit" value="Intégrer le calendrier de la saison <?php
                echo date('Y',time())."/";
                echo date('Y',time())+1; 
            ?>" style="width:330px" />
            </div>
        </form>
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
		</div>
		<div class="foot"><a href="<?php echo $url_home; ?>">Retour &agrave; l'accueil</a> - Manon Prod</div>
	</body>
</html>