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
  XHTML + Javascript
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
- Sous windows: https://www.ionos.fr/digitalguide/serveur/outils/tutoriel-xampp-creer-un-serveur-de-test-local/
- Sous Linux: https://www.cherryservers.com/blog/how-to-install-linux-apache-mysql-and-php-lamp-stack-on-ubuntu-20-04
- Sous MacOS: https://vinodpandey.com/installing-apache-php-mysql-phpmyadmin-mac-os-x/

### Installation de l'application dans le dossier web
- Trouver le dossier web(du serveur web) sur la machine
- Clone le projet avec git(version control system): #### git clone https://github.com/nomUtilisateur/gestionstage.git
