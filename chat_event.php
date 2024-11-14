<?php
// Récupérer l'ID de l'événement et afficher le dashboard
$event_id = $_GET['event_id'];
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Chat de l'événement</title>
    <script src="https://cdn.socket.io/4.1.3/socket.io.min.js"></script>
    <style>
        /* Styles généraux */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7f6;
        }

        /* Styles de la navigation */
        .navbar {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: space-around;
            background-color: #2C3E50;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .navbar li {
            display: inline;
        }

        .navbar a {
            color: #ecf0f1;
            text-decoration: none;
            padding: 15px 20px;
            display: block;
            font-size: 16px;
            transition: all 0.3s ease;
            border-radius: 5px;
        }

        .navbar a:hover {
            background-color: #3498db;
            color: white;
        }

        .navbar a:active {
            background-color: #2980b9;
            color: white;
        }

        @media screen and (max-width: 600px) {
            .navbar {
                flex-direction: column;
                align-items: center;
            }

            .navbar a {
                width: 100%;
                text-align: center;
            }
        }

        #chat-container {
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            margin: 20px;
        }

        #messages {
            width: 100%;
            height: 300px;
            overflow-y: auto;
            background-color: #ffffff;
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            max-width: 800px;
        }

        #messageInput {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-bottom: 10px;
            max-width: 800px;
            box-sizing: border-box;
        }

        #sendMessage {
            background-color: #3498db;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            max-width: 800px;
        }

        #sendMessage:hover {
            background-color: #2980b9;
        }

        .message {
            padding: 8px;
            border-radius: 5px;
            margin-bottom: 5px;
            background-color: #ecf0f1;
            max-width: 75%;
        }

        .message.user {
            background-color: #3498db;
            color: white;
            align-self: flex-end;
        }

        .message.bot {
            background-color: #ecf0f1;
            color: #2c3e50;
            align-self: flex-start;
        }

        @media screen and (max-width: 600px) {
            #chat-container {
                margin: 10px;
            }

            #messages,
            #messageInput,
            #sendMessage {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <ul>
        <ul class="navbar">
            <li><a href="dashboard.php">Tableau de bord</a></li>
            <li><a href="events.php">Événements</a></li>
            <li><a href="logout.php">Déconnexion</a></li>
        </ul>

        <!-- Chat pour l'événement -->
        <h1>Chat pour l'événement #<?= $event_id ?></h1>
        <div id="messages"></div>
        <input type="text" id="messageInput" placeholder="Écrivez votre message...">
        <button id="sendMessage">Envoyer</button>

        <script>
            const socket = io('http://localhost:4001'); // Connexion à Socket.IO
            const event_id = <?= $event_id ?>; // ID de l'événement récupéré depuis l'URL

            // Rejoindre la salle spécifique à cet événement
            socket.emit('joinEvent', event_id);

            // Recevoir les messages et les afficher
            socket.on('message', (message) => {
                const messageDiv = document.createElement('div');
                messageDiv.classList.add('message');
                messageDiv.textContent = message;
                document.getElementById('messages').appendChild(messageDiv);
            });

            // Envoyer un message
            document.getElementById('sendMessage').addEventListener('click', () => {
                const message = document.getElementById('messageInput').value;
                if (message.trim()) {
                    socket.emit('message', {
                        event_id: event_id,
                        message: message
                    });
                    document.getElementById('messageInput').value = ''; // Réinitialiser le champ
                }
            });

            // Permettre l'envoi avec la touche 'Entrée'
            document.getElementById('messageInput').addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    document.getElementById('sendMessage').click();
                }
            });
        </script>
</body>

</html>