<?php

// Mise à jour des mots de passe avec la fonction password_hash()
// Il est nécessaire de regénérer des mots de passe et les mettre à jour dans la base de données
// Pour générer une liste de mots de passe aléatoire :
// Lancer le script depuis le serveur avec la commande : php new_passwd.php
// Puis mettre à jour le hash dans la base de donnée et notifier l'utilisateur de son nouveau mot de passe

function random_str(
					$length,
					$keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
) {
    $str = '';
    $max = mb_strlen($keyspace, '8bit') - 1;
    if ($max < 1) {
        throw new Exception('$keyspace must be at least two characters long');
    }
    for ($i = 0; $i < $length; ++$i) {
        $str .= $keyspace[random_int(0, $max)];
    }
    return $str;
}

$options = [
    'cost' => 07 // Ajuster le coût au besoin selon votre benchmark 
];

for( $i = 0; $i<100; $i++ ) { // Ajuster la valeur 100 en fonction du nombre de mots de passe souhaité
	$plain = random_str(20);
	$hash = password_hash($plain, PASSWORD_BCRYPT, $options);
	echo $plain . ';' . $hash . PHP_EOL ;
    }
?>
