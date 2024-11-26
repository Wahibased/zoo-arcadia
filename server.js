const express = require('express');
const bodyParser = require('body-parser');
const cors = require('cors');
const connectDB = require('/config/db');
const Consultation = require('./models/Consultation');

// Créer une instance d'Express
const app = express();
const port = 3009;

// Connexion à la base de données MongoDB
connectDB();

// Middleware pour permettre le parsing des données JSON
app.use(cors());
app.use(bodyParser.json());

// Route pour enregistrer une nouvelle consultation (incrémentation)
app.post('/api/habitat_consultation', async (req, res) => {
  const { animalId, incrementCount, lastIncrementDate } = req.body;

  if (!animalId || incrementCount == null || !lastIncrementDate) {
    return res.status(400).json({ message: 'Données manquantes' });
  }

  try {
    // Créer une nouvelle entrée de consultation avec les données reçues
    const consultation = new Consultation({
      animalId,
      incrementCount,
      lastIncrementDate,
    });

    // Sauvegarder l'entrée dans la base de données MongoDB
    await consultation.save();

    // Répondre avec un message de succès
    res.status(200).json({ message: 'Données enregistrées avec succès' });
  } catch (error) {
    console.error('Erreur lors de l\'enregistrement des données:', error);
    res.status(500).json({ message: 'Erreur du serveur' });
  }
});

// Démarrer le serveur Express
app.listen(port, () => {
  console.log(`Serveur démarré sur http://localhost:${port}`);
});




