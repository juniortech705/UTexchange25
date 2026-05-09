# Procédure de Lancement du Projet UTexchange25

Cette procédure explique comment lancer le projet UTexchange25 après l'avoir cloné depuis GitHub. Elle couvre les étapes pour Windows et Linux.

## Prérequis

Assurez-vous d'avoir installé les logiciels suivants :
- Git (pour cloner le repository)
- PostgreSQL (pour la base de données)
- PHP (version 8.4 ou supérieure recommandée)
- Python (pour le chatbot, version 3.8 ou supérieure)

## Étapes Générales Avant le Lancement

### 1. Cloner le Repository
```bash
git clone https://github.com/votre-repo/UTexchange25.git
cd UTexchange25
```

### 2. Configuration des Variables d'Environnement

#### À la Racine du Projet
Créez un fichier `.env` à la racine du projet en copiant le contenu de `.env.example` et en ajustant les valeurs selon votre environnement.

Exemple de contenu pour `.env` :
```
DB_HOST=localhost
DB_PORT=5432
DB_NAME=utexchangedb
DB_USER=votre_nom_utilisateur
DB_PASS=votre_mot_de_passe
```

#### Dans le Dossier `chatbot-nlp`
Créez un fichier `.env` dans le dossier `chatbot-nlp` en copiant le contenu de `.env.example` et en ajustant les valeurs selon votre environnement.

Exemple de contenu pour `.env` dans `chatbot-nlp` :
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
Assurez-vous d'importer la base de données `utexchangedb` dans votre instance PostgreSQL. Vous pouvez le faire via la ligne de commande ou via pgAdmin.

Exemple via ligne de commande (remplacez `votre_utilisateur` et `chemin/vers/dump.sql` par les valeurs appropriées) :
```bash
psql -U votre_utilisateur -d utexchangedb -f chemin/vers/dump.sql
```

## Lancement sur Windows

### Prérequis Spécifiques
- Installez XAMPP (qui inclut Apache, PHP et MySQL, mais nous utiliserons PostgreSQL séparément).

### Étapes
1. Copiez le projet dans le dossier `htdocs` de XAMPP (ex. : `C:\xampp\htdocs\UTexchange25`).

2. Lancez Apache via le panneau de contrôle XAMPP.

3. Ouvrez un terminal (Command Prompt ou PowerShell).

4. Naviguez vers le dossier du projet dans XAMPP (htdocs). Exemple :
   ```
   cd "C:\xampp\htdocs\UTexchange25"
   ```

5. Lancez le serveur PHP intégré :
   ```
   C:\xampp\php\php.exe -S localhost:8000
   ```

### En Cas d'Erreur (Extensions PostgreSQL)
Si vous rencontrez une erreur liée à PostgreSQL :

1. Ouvrez le fichier `php.ini` dans XAMPP :
   ```
   C:\xampp\php\php.ini
   ```

2. Activez les extensions PostgreSQL en décommentant (supprimez le `;`) ces lignes :
   ```
   extension=pgsql
   extension=pdo_pgsql
   ```

3. Sauvegardez le fichier et redémarrez Apache via le panneau de contrôle XAMPP.

4. Relancez le terminal et répétez les étapes de lancement.

### Mise à Jour de PHP dans XAMPP
Pour mettre à jour PHP vers la version 8.4.21 (Thread Safe) :

1. Allez dans le dossier XAMPP et créez une copie de sauvegarde du dossier `php` (ex. : "php 8.2 backup").

2. Téléchargez la version PHP 8.4.21 Thread Safe en ZIP depuis le site officiel de PHP.

3. Extrayez le contenu du ZIP.

4. Copiez le contenu du dossier extrait `php-8.4.21` dans le dossier `php` original de XAMPP, en remplaçant les fichiers existants.

5. Redémarrez Apache.

Pour plus de détails, consultez cette vidéo YouTube : https://www.youtube.com/watch?v=6aSytZJRUtw

## Lancement sur Linux

### Prérequis Spécifiques
- Vérifiez qu'Apache est installé :
  ```bash
  apache2 -v
  ```
  Si ce n'est pas le cas, installez-le :
  ```bash
  sudo apt update
  sudo apt install apache2
  ```

- Vérifiez que PHP est installé :
  ```bash
  php -v
  ```
  Si ce n'est pas le cas, installez-le :
  ```bash
  sudo apt update
  sudo apt install php php-cli
  ```

- Installez l'extension PHP mbstring :
  ```bash
  sudo apt install php-mbstring
  ```

### Étapes
1. Naviguez vers le dossier du projet :
   ```bash
   cd /chemin/vers/UTexchange25
   ```

2. Lancez le serveur PHP intégré :
   ```bash
   php -S localhost:8000
   ```

## Étapes Finales (Windows ou Linux)

1. Accédez au README.md du dossier `chatbot-nlp` et suivez toutes les étapes décrites.
   - **Note** : Si un dossier `venv` existe dans `chatbot-nlp`, supprimez-le et suivez les étapes du README.

2. Démarrez l'application via le terminal comme indiqué ci-dessus. Assurez-vous que le serveur du chatbot est bien lancé (généralement sur le port 5000).

3. Ouvrez votre navigateur et allez sur `http://localhost:8000` pour accéder à l'application.

Si vous rencontrez des problèmes, vérifiez les logs du terminal et assurez-vous que toutes les dépendances sont installées.
