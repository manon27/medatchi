<?php
include('config.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="<?php echo $design; ?>/style.css" rel="stylesheet" title="Style" />
        <title>Liste des utilisateurs</title>
    </head>
    <body>
        <div class="header">
            <a href="<?php echo $url_home; ?>"><img src="<?php echo $design; ?>/images/logo.png" alt="Espace Membre" /></a>
        </div>
        <div class="content">
        <a href="connexion.php" style="float:right;"><img src="default\images\exit.png" style="width:20px;"></a>

Voici la liste des utilisateurs:
<table>
    <tr>
        <th>Pseudo</th>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Email</th>
        <th>Rôle</th>
    </tr>
<?php
//On recupere les identifiants, les pseudos, les noms, les prenoms et les emails des utilisateurs
$numRole = 1;
if ($_SESSION['id_role']==1)
{
    $numRole = 0;
}
$req = mysqli_query($connexion, 'select id, pseudo, nom, prenom, email, id_role from membre where id_role > 0');
while($dnn = mysqli_fetch_array($req))
{
    $req2 = mysqli_fetch_array(mysqli_query($connexion, 'select titre from role where id = '.$dnn['id_role']));

?>
    <tr>
        <td class="left"><a href="profile.php?id=<?php echo $dnn['id']; ?>"><?php echo htmlentities($dnn['pseudo'], ENT_QUOTES, 'UTF-8'); ?></a></td>
        <td class="left"><?php echo $dnn['nom']; ?></td>
        <td class="left"><?php echo $dnn['prenom']; ?></td>
        <td class="left"><?php echo htmlentities($dnn['email'], ENT_QUOTES, 'UTF-8'); ?></td>
        <td class="left"><?php echo $req2['titre']; ?></td>
    </tr>
<?php
}
?>
</table>
        </div>
        <div class="foot"><a href="<?php echo $url_home; ?>">Retour &agrave; l'accueil</a> - Manon Prod</div>
    </body>
</html>