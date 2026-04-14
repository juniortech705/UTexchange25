import json
import numpy as np
import torch
import torch.nn as nn
from torch.utils.data import Dataset, DataLoader
from app.utils.nlp_utils import tokenize, stem, bag_of_words
from app.models.neural_net import NeuralNet

# Charger les intents
with open('data/intents/intents.json', 'r', encoding='utf-8') as f:
    intents = json.load(f)

# Listes pour stocker les données
all_words = []  # Tous les mots uniques
tags = []       # Tous les tags (intents)
xy = []         # Paires (pattern, tag)

# Parser les intents
print("📚 Chargement des données...")
for intent in intents['intents']:
    tag = intent['tag']
    tags.append(tag)
    
    for pattern in intent['patterns']:
        # Tokenizer chaque pattern
        words = tokenize(pattern)
        all_words.extend(words)
        xy.append((words, tag))

print(f" {len(xy)} patterns chargés")
print(f" {len(tags)} tags trouvés: {tags}")