<?php
	// On définit les infos de la base de données
	$host = "localhost"; //nom du serveur MySQL
	$user = "root"; //nom de l'utilisateur
	$pass = "";  //son mot de passe
	$db = "dbaboo"; //la base où se connecter
		
	$date = date("d-m-Y"); // On définit le variable $date (ici, son format)

	$backup = $db."Aboo-mysql-backup_".$date.".sql.gz";
	// Utilise les fonctions système : MySQLdump & GZIP pour générer un backup gzipé
	$command = "mysqldump -h$host -u$user -p$pass $db | gzip> $backup";
	system($command);
	// Démarre la procédure de téléchargement
	$taille = filesize($backup);
	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: public");
	header("Content-Description: File Transfer");
	header("Content-Type: application/gzip");
	header("Content-Disposition: attachment; filename=$backup;");
	header("Content-Transfer-Encoding: binary");
	header("Content-Length: ".$taille);
	@readfile($backup);
	// Supprime le fichier temporaire du serveur
	unlink($backup);