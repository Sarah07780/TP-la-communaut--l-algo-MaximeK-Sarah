<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
	//recuperer les information de l'utilisateur
    $nom = trim($_POST["nom"]);
    $prenom = trim($_POST["prenom"]);
    $email = trim($_POST["mail"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $age = $_POST["age"];
    $sexe = $_POST["sexe"];
    $adresse = $_POST["adresse"];
    $ville = $_POST["ville"];
    $zip = $_POST["zip"];
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
            if ($data[2] === $email) {
                die("Erreur : Cet email est déjà enregistré.");
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


