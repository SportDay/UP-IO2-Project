<?php

// INTIALISATION DE LA BASE DE DONNEE

if (true) { 
    // PAR DEFAUT ON NE VEUT PAS QUE QUELQU'UN PUISSE LANCER CE FICHIER PAR ERREUR
    exit();
}

// CREATION DES TABLES



// REMPLISSAGE DES TABLES

$nBots = 20;
users = [
    [
        "username"  =>  "Carl",
        "password"  =>  "Vanille1", 
        "admin"     =>  TRUE 
    ],[
        "username"  =>  "Wilfrid",
        "password"  =>  "Vanille2", 
        "admin"     =>  FALSE 
    ],[
        "username"  =>  "Leila",
        "password"  =>  "Vanille3", 
        "admin"     =>  FALSE
    ],[
        "username"  =>  "Fred",
        "password"  =>  "Vanille4", 
        "admin"     =>  FALSE 
    ]
]


// CREATION DE COMPTES CLASSIQUES (sans page publique par défaut)
foreach($users as $user) {
    addUser();
}

// CREATION DE PAGES PUBLICS
for ($i = 0; $i < $nBots; $i++) {
    addBot();
}

?>

<?php

function addUser() {

}

function addBot() {

}

?>