-- Voici les requêtes pour mettre à jour les mots de passe dans une version hashé
-- On a obtenu chacun de ces mots de passe avec la fonction password_hash('mot de passe', PASSWORD_ARGON2ID)

ALTER TABLE `app_user`
CHANGE `password` `password` varchar(120) COLLATE 'utf8mb4_general_ci' NOT NULL AFTER `email`;

UPDATE app_user set `password` = '$argon2id$v=19$m=65536,t=4,p=1$ZVVZNVROTmRtMXVZbUNNYg$3UeQcG5aWQGwDLa5DiNYrX6/bjnG/ogJkN4miXuqUJ0'
WHERE id = 1;

UPDATE app_user set `password` = '$argon2id$v=19$m=65536,t=4,p=1$a0EzdHhsbWx3Q2RENWlHbA$f0OBIFN73k35ndm2daZNxrzNk2m6j1PNmiT+Lsmc/qo'
WHERE id = 2;

UPDATE app_user set `password` = '$argon2id$v=19$m=65536,t=4,p=1$blNGVVVHV1pmUThDcG8ubQ$+rAm/ZcPu51UgSjEvnZ2aIL43o78W5R1dMCmr9yIDT0'
WHERE id = 3; 