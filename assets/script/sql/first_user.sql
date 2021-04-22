use reseau;

DELETE FROM users;

-- ATTENTION LES MOTS DE PASSENT VONT ETRE HASH (avec SALT)
-- Donc, mdp_admin=lune1 et mdp_roberto=roberta1

INSERT INTO `users` (`username`,    `password`,     `admin`) VALUES  
                    ("admin",       "lune1",        TRUE),
                    ("roberto",     "roberta1",     FALSE);