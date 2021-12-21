# e-commerce-grp2

### installation du CLI symfony 
https://get.symfony.com/cli/setup.exe

### Cloner le projet   
git clone https://github.com/FERRERO-Franck-Formateur-Ajali/e-commerce-grp2.git
  
### Installer les dépendances/vendor    
composer install  
  
### Fichier environnement  
Copier et coller le fichier __.env__ à la racine du projet  
Renommer la copie en __.env.local__  
  
### Configurer le fichier .env.local avec vos identifiants SQL  
DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7  
  
### Créer la database   
php bin/console doctrine:database:create   
  
### Ajouter les entités a la database    
php bin/console make:migration  
php bin/console doctrine:migrations:migrate  
  
### Installtion certificat let's encrypt (https)
symfony server:ca:install

### lancer le starter-kit  
symfony server:start  

Va sur l'url /mdp pour recuperer le mot de passe hasher    
 