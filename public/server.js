// Importation des dépendances
const express = require('express');
const mongoose = require('mongoose');
const cors = require('cors');
const bodyParser = require('body-parser');

// Initialiser l'application Express
const app = express();

// Middleware pour permettre les requêtes CORS
app.use(cors());

// Middleware pour parser le corps des requêtes en JSON
app.use(bodyParser.json());

// Connexion à MongoDB Atlas
mongoose.connect('mongodb+srv://wahiba1988:wahiba1988@cluster0.uju72.mongodb.net/zoodb', {
 
})
  .then(() => console.log('Connecté à MongoDB Atlas'))
  .catch((error) => console.log('Erreur de connexion à MongoDB:', error));

// Schéma de l'incrémentation des animaux
const animalIncrementSchema = new mongoose.Schema({
  habitat: { type: String, required: true },
  animalId: { type: String, required: true },
  incrementCount: { type: Number, required: true },
  lastIncrementDate: { type: Date, required: true }
});

// Modèle basé sur le schéma
const AnimalIncrement = mongoose.model('AnimalIncrement', animalIncrementSchema);

// Route pour enregistrer ou mettre à jour un incrément d'animal
app.post('/api/animalIncrements', async (req, res) => {
  const { habitat, animalId, incrementCount, lastIncrementDate } = req.body;

  try {
    // Mise à jour ou création de l'incrément dans la base de données
    const updatedIncrement = await AnimalIncrement.findOneAndUpdate(
      { habitat, animalId }, // Condition de recherche
      { incrementCount, lastIncrementDate: new Date(lastIncrementDate) }, // Mise à jour des champs
      { new: true, upsert: true } // Options : retour du document mis à jour ou créé
    );

    // Envoyer une réponse de succès
    res.status(200).json({
      message: 'Incrément enregistré avec succès',
      data: updatedIncrement
    });
  } catch (error) {
    console.error('Erreur lors de l\'enregistrement :', error);
    res.status(500).json({
      message: 'Erreur serveur',
      error: error.message
    });
  }
});

// Route pour récupérer tous les incréments d'animaux
app.get('/api/animalIncrements', async (req, res) => {
  try {
    const increments = await AnimalIncrement.find(); // Récupère tous les documents
    res.status(200).json({
      message: "Données récupérées avec succès",
      increments: increments // Retourne la liste des incréments
    });
  } catch (err) {
    res.status(500).json({
      message: "Erreur lors de la récupération des données",
      error: err.message
    });
  }
});

// Démarrer le serveur sur le port 3012
const PORT = process.env.PORT || 3012;
app.listen(PORT, () => {
  console.log(`Le serveur fonctionne sur le port ${PORT}`);
});






