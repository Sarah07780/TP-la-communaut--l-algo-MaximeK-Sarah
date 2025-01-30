<?php

	session_start(); // Démarrer la session
	if ($_SERVER["REQUEST_METHOD"] === "POST") {
		//recuperer les information de l'utilisateur
		$nom = htmlspecialchars(trim($_POST["nom"]), ENT_QUOTES, 'UTF-8');
		$prenom = htmlspecialchars(trim($_POST["prenom"]), ENT_QUOTES, 'UTF-8');
		$email = htmlspecialchars(trim($_POST["mail"]), ENT_QUOTES, 'UTF-8');
		$password = $_POST["password"];
		$confirm_password = $_POST["confirm_password"];
		$age = $_POST["age"];
		$sexe = $_POST["sexe"];
		$adresse = htmlspecialchars(trim($_POST["adresse"]), ENT_QUOTES, 'UTF-8');
		$ville = htmlspecialchars(trim($_POST["ville"]), ENT_QUOTES, 'UTF-8');
		$zip = htmlspecialchars($_POST["zip"], ENT_QUOTES, 'UTF-8');
		$nationalite = $_POST["nationalite"];
		$pays_naissance = $_POST["pays_naissance"];
		$loisirs = isset($_POST["loisirs"]) ? $_POST["loisirs"] : [];
		$avatar = $_POST["avatar"];
		$consentement = isset($_POST["consentement"]);


		// Vérification du mot de passe
		if ($password !== $confirm_password) {
			die("Erreur : Les mots de passe ne correspondent pas.");
		}

		//verrifie si le nombre de loisir et entre 2 et 4
		if (count($loisirs) < 2 || count($loisirs) > 4) {
			die("Erreur : Vous devez sélectionner entre 2 et 4 loisirs.");
		}

		// Vérification si l'email existe déjà

		$file = "utilisateur.txt";
		if (file_exists($file)) {
			$users = file($file, FILE_IGNORE_NEW_LINES);
			foreach ($users as $user) {
				$data = explode(";", $user);
				if ($data[2] === $email) { // Vérifie si l'email est déjà enregistré
					$_SESSION["error"] = "Une erreur est survenue : cet email est déjà utilisé.";

					// Stocker les champs saisis dans la session (sauf email et mots de passe)
					$_SESSION["form_data"] = [
						"nom" => $nom,
						"prenom" => $prenom,
						"age" => $age,
						"sexe" => $sexe,
						"adresse" => $adresse,
						"ville" => $ville,
						"zip" => $zip,
						"nationalite" => $nationalite,
						"pays_naissance" => $pays_naissance,
						"loisirs" => $loisirs
					];

					header("Location: index.php"); // Rediriger vers le formulaire
					exit();
				}
			}
		}

		// Enregistrement des données
		$loisirs_str = implode(",", $loisirs); // Convertir le tableau en chaîne
		$utilisateur = "$nom;$prenom;$email;$password;$confirm_password;$age;".
		"$sexe;$adresse;$ville;$zip;$nationalite;$pays_naissance;$loisirs_str;$avatar\n";
		file_put_contents($file, $utilisateur, FILE_APPEND);
		echo "Bienvenue sur le site, $prenom $nom !";
	}
?>


