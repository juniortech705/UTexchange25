# Procédure pour Exécuter le Projet UTexchange25 après Copie du Dossier

Cette procédure explique comment lancer le projet UTexchange25 si vous avez copié le dossier du projet (par exemple, via USB, partage réseau, etc.) et souhaitez l'exécuter localement. Les étapes sont similaires à celles de `lancement.md`, mais adaptées au fait que les fichiers `.env` sont déjà présents et doivent être modifiés. Pour les détails complets sur les prérequis et les commandes, consultez `lancement.md`.

## Prérequis

Assurez-vous d'avoir installé les logiciels suivants (voir `lancement.md` pour plus de détails) :
- PostgreSQL (pour la base de données)
- PHP (version 8.4 ou supérieure recommandée)
- Python (pour le chatbot, version 3.8 ou supérieure)

## Étapes Générales Avant le Lancement

### 1. Copier le Dossier du Projet
- Copiez le dossier `UTexchange25` vers votre environnement local (par exemple, sur Windows dans `C:\xampp\htdocs\` ou sur Linux dans `/var/www/html/`).

### 2. Configuration des Variables d'Environnement

#### À la Racine du Projet
Les fichiers `.env` sont déjà présents. Modifiez-les selon votre environnement.

- Ouvrez `.env` à la racine et ajustez les valeurs (exemples fournis dans `lancement.md`) :
  ```
  DB_HOST=localhost
  DB_PORT=5432
  DB_NAME=utexchangedb
  DB_USER=votre_nom_utilisateur
  DB_PASS=votre_mot_de_passe
  ```

#### Dans le Dossier `chatbot-nlp`
Modifiez le fichier `.env` dans `chatbot-nlp` selon votre environnement (exemples dans `lancement.md`) :
  ```
  # Configuration Flask
  FLASK_APP=main.py
  FLASK_ENV=development
  FLASK_DEBUG=True

  # Configuration serveur
  HOST=0.0.0.0
  PORT=5000

  # Configuration chatbot
  MODEL_NAME=chatbot_model.pth
  MAX_LENGTH=100
  ```

### 3. Importer la Base de Données
Assurez-vous d'importer la base de données `utexchangedb` dans votre instance PostgreSQL. Consultez `lancement.md` pour les commandes via ligne de commande ou pgAdmin.

## Lancement sur Windows

Consultez la section "Lancement sur Windows" dans `lancement.md` pour les étapes détaillées, y compris la gestion des erreurs et la mise à jour de PHP.

## Lancement sur Linux

Consultez la section "Lancement sur Linux" dans `lancement.md` pour les étapes détaillées, y compris l'installation d'Apache, PHP et l'extension mbstring.

## Étapes Finales (Windows ou Linux)

1. Accédez au README.md du dossier `chatbot-nlp` et suivez toutes les étapes décrites (y compris la suppression du dossier `venv` si présent et l'exécution des commandes pour installer les dépendances Python, entraîner le modèle si nécessaire, etc.).
   - **Note** : Si un dossier `venv` existe dans `chatbot-nlp`, supprimez-le et suivez les étapes du README.

2. Démarrez l'application via le terminal comme indiqué dans `lancement.md`. Assurez-vous que le serveur du chatbot est bien lancé (généralement sur le port 5000).

3. Ouvrez votre navigateur et allez sur `http://localhost:8000` pour accéder à l'application.

Si vous rencontrez des problèmes, vérifiez les logs du terminal et assurez-vous que toutes les dépendances sont installées. Pour plus de détails, reportez-vous à `lancement.md`.
