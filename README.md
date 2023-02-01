# Contexte
Affecter les tuteurs aux suivies des stages pour les étudiants
# Architecture
### Backend
```
  PHP + Doctrine-Migration + SGBD Mysql
  Base de donnée <-> DAO(Data Access Object) <-> Model <-> Controlleur <-> API(format JSON)
```
### Frontend
```
  HTML5 + Javascript
  Navigateur <-> API(backend)
```
## Description
Cette application web a pour but de gérer l'attribution des tuteurs pour la suivie des stages, elle a comme fonctionnalités:
- Enregistrement des stages par les étudiants et aussi les enseignents qui propose des stages
- L'étudiant pourra choisir un stage proposé par un enseignant
- L'enseignant pourra s'auto-affecter un stage enregistré par un étudiant
- Le responsable des stages pourra valider les affectations déjà faite
- Le responsable des stages pourra affecter aux tuteurs les stages qui n'ont pas encore de tuteurs.

# Comment installer et lancer le projet

## Installation

### Mise en place de l'environnement
#### Installation de php, mysql et apache
- Sous windows: `https://www.ionos.fr/digitalguide/serveur/outils/tutoriel-xampp-creer-un-serveur-de-test-local/`
- Sous Linux: `https://www.cherryservers.com/blog/how-to-install-linux-apache-mysql-and-php-lamp-stack-on-ubuntu-20-04`
- Sous MacOS: `https://vinodpandey.com/installing-apache-php-mysql-phpmyadmin-mac-os-x/`

#### Installation de composer
- À la fois sur linux, windows et macOS: `https://www.hostinger.com/tutorials/how-to-install-composer`

### Installation de l'application dans le dossier web
- Se déplacer dans le dossier web(du serveur web) sur la machine
- Cloner le projet avec git(version control system): `git clone https://github.com/hakifran/gestionstage.git`
- Créer une base de donnée dans mysql: `CREATE DATABASE nom_de_la_base_de_donnee`
- Modifier le fichier `migrations-db.php`
    ```
    return [
        'dbname' => 'nom_de_la_base_de_donnee',
        'user' => 'nom_utilisateur',
        'password' => 'mot_de_pass',
        'host' => 'adresse_de_la_machine_qui_heberge_la_BD',
        'driver' => 'pdo_mysql',
    ];
    ```
- Lancer les migrations: `./vendor/bin/doctrine-migrations migrate`

### Lancer l'application
- Ouvrir le navigateur
- Mettre l'addresse suivant dans la barre d'addresse du navigateur: `http://localhost/gestionstage`

