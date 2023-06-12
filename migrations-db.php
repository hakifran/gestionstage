<?php
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

return [
    'dbname' => $_ENV["NOM_BASE_DE_DONNEE"],
    'user' => $_ENV["NOM_UTILISATEUR_BD"],
    'password' => $_ENV["MOT_DE_PASS_UTILISATEUR_BD"],
    'host' => $_ENV["ADDRESSE_MACHINE_HOST_BD"],
    'driver' => 'pdo_mysql',
];
