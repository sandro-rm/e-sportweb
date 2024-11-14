<?php
// events.php

session_start();
require_once('config.php');

// Requête pour récupérer tous les événements validés (pour tout le monde)
$query = "SELECT * FROM events WHERE status = 'validé' ORDER BY start_date ASC";
$stmt = $pdo->query($query);
$events = $stmt->fetchAll();



$stmt = $pdo->prepare("SELECT events.*, users.username FROM events JOIN users ON events.created_by = users.id WHERE events.status = 'validé' ORDER BY start_date DESC");
$stmt->execute();
$events = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Événements E-sport</title>
    <style>
        /* Style général de la page */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #0f0f0f;
            color: #fff;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #222;
            padding: 15px 30px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav a {
            color: #00acee;
            text-decoration: none;
            font-size: 18px;
            padding: 10px 20px;
            border-radius: 4px;
            transition: background 0.3s, color 0.3s;
        }

        nav a:hover {
            background: #00acee;
            color: #fff;
        }

        .event-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            gap: 20px;
            margin-top: 20px;
        }

        .event-card {
            background-color: #1a1a1a;
            border-radius: 12px;
            width: 300px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .event-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.6);
        }

        .event-title {
            font-size: 1.8em;
            color: #00acee;
            margin-bottom: 15px;
            text-align: center;
        }

        .event-description {
            font-size: 1em;
            color: #ccc;
            margin-bottom: 15px;
            text-align: center;
        }

        .event-meta {
            color: #888;
            font-size: 0.9em;
            text-align: center;
            margin-bottom: 20px;
        }

        .join-button {
            display: block;
            width: 100%;
            text-align: center;
            background-color: #00acee;
            color: #fff;
            padding: 10px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 1em;
            transition: background-color 0.3s ease;
        }

        .join-button:hover {
            background-color: #007bb5;
        }

        footer {
            background-color: #222;
            text-align: center;
            padding: 10px;
            color: #888;
            margin-top: 50px;
        }
    </style>
</head>

<body>
    <header>
        <nav>
            <a href="index.html">Accueil</a>
            <a href="dashboard.php">Tableau de bord</a>
            <a href="login.php">Se connecter</a>
        </nav>
    </header>

    <main>
        <h1 style="text-align: center; color: #fff; font-size: 2.5em;">Événements E-sport à venir</h1>

        <div class="event-list">
            <?php if (count($events) > 0): ?>
                <?php foreach ($events as $event): ?>
                    <div class="event-card">
                        <h3 class="event-title"><?php echo htmlspecialchars($event['title']); ?></h3>
                        <p class="event-description"><?php echo htmlspecialchars($event['description']); ?></p>
                        <div class="event-meta">
                            <p><strong>Créé par :</strong> <?php echo htmlspecialchars($event['username']);  ?> </p>
                            <p><strong>Participants :</strong> <?php echo htmlspecialchars($event['player_count']); ?></p>
                            <p><strong>Date :</strong> <?php echo htmlspecialchars($event['start_date']); ?> - <?php echo htmlspecialchars($event['end_date']); ?></p>
                        </div>

                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center; color: #ccc;">Aucun événement à afficher pour le moment.</p>
            <?php endif; ?>
        </div>
    </main>







    <footer>
        <p>&copy;
            2024 E-sport Events</p>
    </footer>

</body>


</html>