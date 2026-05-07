# API Chatbot NLP

Interface de traitement du langage naturel utilisant PyTorch et Flask.

## Structure
- `/app` : Contient le point d'entrée `main.py`.
- `chatbot_model.pth` : Modèle entraîné (réseau de neurones).
- `requirements.txt` : Liste des dépendances Python.
## Environnement
```bash 
#Preciser la version de Python (ex: python3.8) si nécessaire
python -m venv venv
source venv/bin/activate  # Sur Windows : .\venv\Scripts\activate.ps1
python -c "import nltk; nltk.download('punkt_tab'); nltk.download('punkt'); nltk.download('stopwords')" #si nécessaire pour les ressources NLTK
```
## Installation
```bash
pip install -r requirements.txt
python -m app.main
```
`.env` : Créer un fichier `.env` pour les variables d'environnement dans le dossier `chatbot-nlp` avec pour contenu `.env.example` adapté à votre environnement.
## Test
Pour voir le chatbot en action (mode terminal), exécutez le script de test :
```bash
 python test_chat.py
```