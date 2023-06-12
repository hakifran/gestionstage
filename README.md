# Contexte
Affecter les tuteurs aux suivies des stages pour les étudiants
# Architecture
![hakizimana franck (2)](https://github.com/hakifran/gestionstage/assets/19631540/da30d372-a85f-47f8-a4c0-2c336105d628)
### Backend
```
  PHP + Doctrine-Migration + SGBD Mysql
  Base de donnée <-> DAO(Data Access Object) <-> Model <-> Controlleur <-> API(format JSON)
```
```bash
├── Gestionstage
  ├── app
  │   ├── controllers # Dossier les controllers
  │   │   ├── tous les controllers.php
  │   ├── core # Dossier de configuration de l'architecture MVC
  │   │   ├── App.php
  │   │   └── Controller.php
  │   ├── db # Dossier pour la gestion de la Base de Données
  │   │   ├── basededonnee.php # connection à la Base de Données
  │   │   └── dao # Gestion des requêtes SQL pour interagir avec la Base de Données
  │   │    └── tous les fichiers DAO
  │   ├── init.php
  │   ├── models # Dossier contenant les objets représentant les entités de la Base de Données
  │   │ └── tous les fichiers Models
  │   ├── services # Dossier contenant les objets services
  │   │   └── utils.php
  │   └── views
  │       └── home
  ├── composer.json
  ├── composer.lock
  ├── migrations # tous les fichiers de migrations pour Doctrine
  │   ├── Version20230129082637.php
  ├── migrations-db.php # Connecter Doctrine à la Base de Données
  ├── migrations.php # Fichier de configuration de Doctrine
  ├── phpunit.xml
  ├── public # Partie public de l'application
  │   └── index.php
  ├── .env # Le fichier contient les identifiants pour se connecter au service web de l'application
```
### Frontend
```
  HTML5 + Javascript
  Navigateur <-> API(backend)
```
``` bash
├── frontend
│   ├── css # Fichier css de bootstrap
│   │   ├── bootstrap.css
│   ├── csspersonnalise # Fichier css de personnalisation
│   │   ├── inscription.css
│   │   ├── login.css
│   │   └── template.css
│   ├── html # Fichier html pour écrire le contenu
│   │   ├── footer.php
│   │   ├── index.php
│   │   ├── inscriptionutilisateur.php
│   │   ├── template.php
│   │   └── text_accueil.php
│   ├── jquery-3.6.4.js # Le fichier de la librairie jquery
│   └── js # Les fichier js de bootstrap
│       ├── bootstrap.js
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


