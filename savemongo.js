const { MongoClient } = require('mongodb');
// Remplacez par votre URI
const uri = "mongodb+srv://wahiba1988:wahiba1988@cluster0.uju72.mongodb.net/"; 
const client = new MongoClient(uri);

async function saveConsultation(habitat, animalId, count, timestamp) {
    try {
        await client.connect();
        const database = client.db('zoodb');
        const consultations = database.collection('consultations');

        const filter = { habitat: habitat, animalId: animalId };
        const update = {
            $set: { habitat, animalId },
            $inc: { count: count },
            $setOnInsert: { firstConsultation: timestamp },
            $set: { lastConsultation: timestamp }
        };
        const options = { upsert: true };

        const result = await consultations.updateOne(filter, update, options);
        console.log("Consultation enregistrée ou mise à jour :", result);
    } catch (err) {
        console.error("Erreur lors de l'enregistrement :", err);
    } finally {
        await client.close();
    }
}

// Exemple d'utilisation
// saveConsultation("marais", "1", 1, new Date().toISOString());
module.exports = { saveConsultation };
