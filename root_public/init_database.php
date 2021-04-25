<?php

/*
    INIT_DATABASE:
    Activez ce fichier ou ouvrez sa page si vous souhaitez reinitialiser la base donnée.
*/


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
        "username"  =>  "root",
        "password"  =>  "Vanille1", 
        "admin"     =>  TRUE 
    ],[
        "username"  =>  "Carl",
        "password"  =>  "Vanille2", 
        "admin"     =>  TRUE 
    ],[
        "username"  =>  "SportDay",
        "password"  =>  "Vanille3", 
        "admin"     =>  TRUE 
    ],[
        "username"  =>  "Wilfrid",
        "password"  =>  "Vanille4", 
        "admin"     =>  FALSE 
    ],[
        "username"  =>  "Leila",
        "password"  =>  "Vanille5", 
        "admin"     =>  FALSE
    ],[
        "username"  =>  "Fred",
        "password"  =>  "Vanille6", 
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