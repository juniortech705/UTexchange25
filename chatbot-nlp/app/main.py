from flask import Flask
from dotenv import load_dotenv
import os

# Charger les variables d'environnement
load_dotenv()

# Créer l'application Flask
app = Flask(__name__)

# Route de test
@app.route('/')
def hello():
    return {
        'message': 'Chatbot API is running!',
        'status': 'success'
    }



# Point d'entrée
if __name__ == '__main__':
    host = os.getenv('HOST', '127.0.0.1')
    port = int(os.getenv('PORT', 5000))
    debug = os.getenv('FLASK_DEBUG', 'False') == 'True'
    
    print(f"🚀 Starting Flask server on {host}:{port}")
    app.run(host=host, port=port, debug=debug)