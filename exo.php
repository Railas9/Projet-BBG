<!DOCTYPE html>
<html>
<head>
<title>Formulaire de saisie</title>
<style type="text/css">
body {
 background-color:#ffd;
 font-family:Verdana,Helvetica,Arial,sans-serif
 size:10;
}
</style>
</head>
<body>
<h2>Formulaire de saisie d'informations bancaires</h2>
<form action="recup_infosBancaires.php" method="post">
<table>
<tr><td>
Pr&eacute;nom</td><td><input type="text" name="prenom" /></td></tr>
<tr><td>Nom</td><td><input type="text" name="nom" /></td></tr>
<tr><td>Num&eacute;ro de carte bancaire</td><td><input type="text" name="numCB"
/></td></tr>
<tr><td>Cryptogramme</td><td><input type="text" name="crypto" size="3"
maxlength="3"/></td></tr>
</table>
<p>
<input type="reset" name="reset" value="Annulez" />
<input type="submit" name="formBancaire" value="Envoyez" />
</p>
</form>
</body>
</html>