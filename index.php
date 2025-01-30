<?php

	session_start(); // Démarrer la session

	// Récupérer les données du formulaire si elles existent
	$form_data = isset($_SESSION["form_data"]) ? $_SESSION["form_data"] : [];
	$error = isset($_SESSION["error"]) ? $_SESSION["error"] : "";

	// Effacer l'erreur après affichage
	unset($_SESSION["error"]);
	unset($_SESSION["form_data"]);

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
	$nom2 ="oyane"; $prenom2 ="sarah";
	$auteur = fopen("author.txt", "w");
	fputs($auteur, "$nom $prenom\n$nom2 $prenom2\n");
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

		<?php if (!empty($error)) : ?>
			<p style="color: red;"><?= $error ?></p>
		<?php endif; ?>

		<form action="process.php" method="POST">
			<p>
			<label>Nom :</label>
			<input type="text" name="nom" value="<?= htmlspecialchars($form_data['nom'] ?? '') ?>" required />
			<br>

			<label>Prenom :</label>
			<input type="text" name="prenom" value="<?= htmlspecialchars($form_data['prenom'] ?? '') ?>" required />
			<br>

			<label>Mail :</label>
			<input type="email" name="mail" required />
			<!-- PAS de value ici pour éviter de montrer l'email déjà pris -->
			<br>

			<label>Age :</label>
			<input type="number" name="age" value="<?= htmlspecialchars($form_data['age'] ?? '') ?>" required />
			<br>


			<label>Sexe :</label>
			<input type="radio" name="sexe" value="Homme" <?= (isset($form_data["sexe"]) && $form_data["sexe"] == "Homme") ? "checked" : "" ?> required> Homme
			<input type="radio" name="sexe" value="Femme" <?= (isset($form_data["sexe"]) && $form_data["sexe"] == "Femme") ? "checked" : "" ?> required> Femme
			<br>

			<label>Adresse :</label>
			<input type="text" name="adresse" placeholder="Numéro et rue" value="<?= htmlspecialchars($form_data['adresse'] ?? '') ?>" required><br>
			<input type="text" name="ville" placeholder="Ville" value="<?= htmlspecialchars($form_data['ville'] ?? '') ?>" required><br>
			<input type="text" name="zip" placeholder="Code postal" value="<?= htmlspecialchars($form_data['zip'] ?? '') ?>" required>
			<br>
			
			<label>Nationalité :</label>
			<select name="nationalite" required>
				<?php 
				foreach ($nationalities as $n) {
					$selected = (isset($form_data["nationalite"]) && $form_data["nationalite"] == $n) ? "selected" : "";
					echo "<option value='$n' $selected>$n</option>";
				}
				?>
			</select>
			<br>

			<label>Pays de naissance :</label>
			<select name="pays_naissance" required>
				<?php 
				foreach ($countries as $p) {
					$selected = (isset($form_data["pays_naissance"]) && $form_data["pays_naissance"] == $p) ? "selected" : "";
					echo "<option value='$p' $selected>$p</option>";
				}
				?>
			</select>
			<br>
			
			<label>Description :</label>
			<textarea name="description" maxlength="978"><?= htmlspecialchars($form_data["description"] ?? '') ?></textarea>
			<br>

			
			<label>Loisirs :</label>
			<?php
				foreach ($activities as $a) {
					$checked = isset($form_data["loisirs"]) && in_array($a, $form_data["loisirs"]) ? "checked" : "";
					echo "<label><input type='checkbox' name='loisirs[]' value='$a' $checked> $a</label><br>";
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
