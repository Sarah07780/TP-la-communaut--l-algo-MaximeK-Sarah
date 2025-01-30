<?php

	// Fonction pour lire et recuperer les données des fichiers CSV
	function loadOptions($filename, $isCountryFile) {
		$options = []; // Initialisation du tableau
	
		if (($handle = fopen($filename, "r")) !== FALSE) {
			// Lire chaque ligne du fichier
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				// Pour le fichier pays.csv, on veut récupérer le nom du pays
				if ($isCountryFile) {
					$options[] = trim($data[4]); // Récupérer le nom du pays (5e colonne)
				} else {
					// Pour nationalite.csv, on veut récupérer la nationalité
					$options[] = trim($data[0]);
				}
			}
			fclose($handle);
		}
		return $options;
	}
	
	//charger les tableau avec nos fichier
	$nationalities = loadOptions("nationality.csv",false);
	$countries = loadOptions("pays.csv",true);
	$activities = file("activity.txt", FILE_IGNORE_NEW_LINES);

	
	//creation fichier auteur
	$nom ="kohler"; $prenom ="maxime";
	$nom2 ="OYANE"; $prenom2 ="Sarah";
	$auteur = fopen("author.txt", "a");
	fputs($auteur, "$nom $prenom\n $nom2 $prenom2/n");
	fclose($auteur);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<title>Inscription</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
 
	<h2>Formulaire d'inscription</h2>
	<form action="process.php" method="POST">
		<p>
			<label>Nom :</label>
			<input type="text" name="nom" id="name" value="" required/>
			<br>

			<label>Prenom :</label>
			<input type="text" name="prenom" id="surname" value="" required/>
			<br>

			<label>Mail :</label>
			<input type="email" name="mail" id="mail" value="" required/>
			<br>

			<label>Age :</label>
			<input type="number" name="age" id="age" value="" required/>
			<br>

			<label>Sexe :</label>
			<input type="radio" name="sexe" value="Homme" required> Homme
			<input type="radio" name="sexe" value="Femme" required> Femme
			<br>

			<label>Adresse :</label>
			<input type="text" name="adresse" placeholder="Numéro et rue" required><br>
			<input type="text" name="ville" placeholder="Ville" required><br>
			<input type="text" name="zip" placeholder="Code postal" required>
			<br>
			
			<label>Nationalité :</label>
			<select name="nationalite" required>
				<?php foreach ($nationalities as $n) echo "<option value='$n'>$n</option>"; ?>
			</select>
			<br>
			
			<label>Pays de naissance :</label>
			<select name="pays_naissance" required>
				<?php foreach ($countries as $p) echo "<option value='$p'>$p</option>"; ?>
			</select><br>
			
			<label>Description :</label>
			<textarea name="description" maxlength="978"></textarea><br>
			
			<label>Loisirs :</label>
			<?php
				foreach ($activities as $a) {
					echo "<label><input type='checkbox' name='loisirs[]' value='$a'> $a</label><br>";
				}
			?>		
			<br>
			
			<label>Mot de passe :</label> <input type="password" name="password" required><br>
			<label>Confirmer :</label> <input type="password" name="confirm_password" required><br>
			
			<label>Avatar (optionnel) :</label> <input type="file" name="avatar" accept="image/png, image/jpeg, image/gif"><br>
		
			<input type="checkbox" name="consentement" required> J'accepte le traitement de mes données.<br>
		
			<input type="submit" value="S'inscrire">
		</p>
	</form>
</body>
</html>
