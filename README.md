# Contexte
Affecter les tuteurs aux suivies des stages pour les étudiants
# Architecture
![hakizimana franck (2) (1) (1)](https://github.com/hakifran/gestionstage/assets/19631540/6955ea86-7392-4fa0-880e-cc0dee293098)
### Backend
```
  PHP + Doctrine-Migration + SGBD Mysql
  Base de donnée <-> DAO(Data Access Object) <-> Model <-> Controlleur <-> API(format JSON)
```
#### Arborescence du code backend
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
  ├── .env.exemple # Le fichier contient les identifiants pour se connecter au service web de l'application
```
### Frontend
```
  HTML5 + Javascript
  Navigateur <-> API(backend)
```
#### Arborescence du code frontend
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
- Récuperer le dossier contenant le code du projet: 
    - Soit clone le projet à partir de github :`git clone https://github.com/hakifran/gestionstage.git`
    - Ou directement récuperer le dossier
- Créer une base de donnée dans mysql: `CREATE DATABASE nom_de_la_base_de_donnee`
- change le nom du fichier `.env.exemple` en `.env` et change les valeurs des paramètres que contient le fichier 
    ```
    USER_NAME=nomUtilisateur pour l'inscription et la connexion d'un utilisateur
    USER_PASSWORD=mot de pass pour l'inscription et la connexion d'un utilisateur
    ADMIN_NAME=nomUtilisateur pour enregistre un nouveau administrateur via l'API
    ADMIN_PASSWORD=mot de pass pour enregistre un nouveau administrateur via l'API
    NOM_BASE_DE_DONNEE=nom de la base de données
    NOM_UTILISATEUR_BD=nom d'utilisateur de la base de données
    MOT_DE_PASS_UTILISATEUR_BD=mot de pass de l'utilisateur de la base de données 
    ADDRESSE_MACHINE_HOST_BD=l'addresse de la machine hôte qui heberge la base de données
    ```
- Lancer les migrations: `./vendor/bin/doctrine-migrations migrate`

### Lancer l'application
#### Frontend
- Ouvrir le navigateur
- Mettre l'addresse suivant dans la barre d'addresse du navigateur: `http://localhost/gestionstage/frontend/html`
### Backend via l'API
- Précise la Méthode(POST, GET, PATCH)
- L'URL de l'API est composé de principalement quatre parties: `http://localhost/gestionstage/public/stage/get?id=45`
    - L'URL de base: `http://localhost/gestionstage/public/` 
    - Le nom du controlleur: `stage/`
    - Le nom de la méthode pour execute de l'API: `get`
    - Les paramètres à passer: `?id=45`
  


