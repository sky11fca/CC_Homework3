const express = require('express');
const cors = require('cors');
const multer = require('multer');
const vision = require('@google-cloud/vision');

const app = express();
app.use(cors());
app.use(express.json());

// Configurăm Multer pentru a stoca imaginea temporar în memorie
const upload = multer({ storage: multer.memoryStorage() });

// Inițializăm clientul Google Cloud Vision
// Notă: Pe Google App Engine, se va autentifica automat folosind contul de serviciu default
const client = new vision.ImageAnnotatorClient();

app.post('/api/media/upload', upload.single('image'), async (req, res) => {
    try {
        if (!req.file) {
            return res.status(400).json({ error: 'Nicio imagine încărcată.' });
        }

        // Trimitem buffer-ul imaginii către Vision API
        const [result] = await client.labelDetection(req.file.buffer);
        const labels = result.labelAnnotations.map(label => label.description);

        res.status(200).json({
            message: 'Imagine procesată cu succes!',
            tags: labels
        });
    } catch (error) {
        console.error('Eroare la procesarea Vision API:', error);
        res.status(500).json({ error: 'A apărut o eroare la procesarea imaginii.' });
    }
});

const PORT = process.env.PORT || 8080;
app.listen(PORT, () => {
    console.log(`Media Service rulează pe portul ${PORT}`);
});