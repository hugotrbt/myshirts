🏆 MyShirts - Collection de Maillots de Football
MyShirts est une application web de gestion et de collection de maillots de football développée avec Symfony 6.4. Elle permet aux passionnés de football de créer, organiser et partager leurs collections de maillots 

🎯 Concept du Projet
Le projet utilise une métaphore footballistique pour organiser les collections :

Maillots (Shirts) : Les objets de collection avec images, équipes et métadonnées
Stades (Stadium) : Les galeries publiques ou privées pour exposer les maillots
Vestiaires (LockerRoom) : L'espace personnel de chaque membre pour gérer sa collection
Membres (Members) : Les collectionneurs avec différents niveaux d'accès

Pour les Collectionneurs

📸 Upload d'images avec gestion automatique des fichiers (VichUploaderBundle)
🏟️ Création de stades publics ou privés pour organiser les maillots
👕 Gestion complète des maillots (ajout, modification, suppression)
🔒 Vestiaire personnel sécurisé pour gérer sa collection privée
🎨 Interface responsive avec Bootstrap 5

Pour les Administrateurs

👥 Gestion complète des membres
🏢 Vue d'ensemble de tous les vestiaires

🚀 Installation

# Cloner le projet
git clone [url-du-repo]
cd myshirts

# Installation des dépendances
composer install

# Configuration de l'environnement
cp .env .env.local
# Modifier DATABASE_URL dans .env.local

# Création de la base de données
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

# Chargement des fixtures (optionnel)
php bin/console doctrine:fixtures:load

# Lancement du serveur de développement
symfony serve:start

Pour tester l'application, vous pouvez utiliser ces comptes :

Email	Mot de passe	Rôle
hugo@intv.fr	123456	ADMIN
emilien@intv.fr	123456	MEMBER
max@intv.fr	123456	MEMBER

Projet développé dans le cadre du cours CSC4101 - Développement Web avec Symfony
