const express = require('express');
const path = require('path');
const http = require('http');
const socketIo = require('socket.io');
const cors = require('cors');

// Créer une application Express
const app = express();
const server = http.createServer(app);

// Configurer CORS
app.use(cors({
    origin: "http://localhost:3000", // URL de ton frontend
    methods: ["GET", "POST"],
    credentials: true
}));

// Créer le serveur socket.io avec le serveur HTTP
const io = socketIo(server, {
    cors: {
        origin: "http://localhost:3000",  // L'URL de ton frontend
        methods: ["GET", "POST"],
        credentials: true
    }
});

// Servir les fichiers statiques du dossier 'chat-temps-reel'
app.use(express.static(path.join(__dirname, 'chat-temps-reel')));

io.on('connection', (socket) => {
    console.log('Un utilisateur est connecté');

    // Rejoindre une salle spécifique à un événement
    socket.on('joinEvent', (event_id) => {
        socket.join(event_id); // L'utilisateur rejoint la salle de l'événement
        console.log(`Utilisateur a rejoint l'événement ${event_id}`);
    });

    // Recevoir un message et l'envoyer à la salle de l'événement
    socket.on('message', (data) => {
        io.to(data.event_id).emit('message', data.message); // Message envoyé dans la salle de l'événement
    });

    // Déconnexion
    socket.on('disconnect', () => {
        console.log('Un utilisateur a quitté');
    });
});

// Démarrer le serveur sur le port 4001
server.listen(4001, () => {
    console.log('Serveur lancé sur http://localhost:4001');
});
