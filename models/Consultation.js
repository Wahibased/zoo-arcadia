const mongoose = require('mongoose');

// Schéma pour enregistrer les consultations des animaux
const consultationSchema = new mongoose.Schema({
  animalId: {
    type: String,
    required: true, // Assurer que l'animalId est nécessaire
  },
  incrementCount: {
    type: Number,
    required: true, // Assurer que l'incrementCount est nécessaire
  },
  lastIncrementDate: {
    type: Date,
    default: Date.now, // Par défaut, la date actuelle
  },
});

const Consultation = mongoose.model('Consultation', consultationSchema);

module.exports = Consultation;
