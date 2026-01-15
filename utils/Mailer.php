<?php

class Mailer
{
    // Configure l'adresse d'expédition ici
    private static $from = "no-reply@camagru.com";

    /**
     * Envoie le mail de confirmation de compte
     */
    public static function sendAccountConfirmation(string $to, string $username, string $activationLink): bool
    {
        $subject = "Bienvenue sur Camagru ! Confirmez votre compte";
        
        // On construit le message en HTML
        // Tu peux styliser cela avec du CSS inline plus tard
        $message = "
        <html>
        <head>
            <title>Bienvenue sur Camagru</title>
        </head>
        <body>
            <h1>Bonjour " . htmlspecialchars($username) . " !</h1>
            <p>Merci de vous être inscrit sur Camagru.</p>
            <p>Pour activer votre compte et commencer à partager vos photos, veuillez cliquer sur le lien ci-dessous :</p>
            <p>
                <a href='" . htmlspecialchars($activationLink) . "'>Confirmer mon compte</a>
            </p>
            <br>
            <p><small>Si le lien ne fonctionne pas, copiez-collez cette URL dans votre navigateur : " . htmlspecialchars($activationLink) . "</small></p>
        </body>
        </html>
        ";

        return self::send($to, $subject, $message);
    }

    /**
     * Fonction interne privée qui gère la complexité technique de mail()
     */
    private static function send(string $to, string $subject, string $message): bool
    {
        // Headers indispensables pour envoyer du HTML et éviter (un peu) les spams
        $headers = [];
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-Type: text/html; charset=utf-8';
        $headers[] = 'From: Camagru <' . self::$from . '>';
        $headers[] = 'Reply-To: ' . self::$from;
        $headers[] = 'X-Mailer: PHP/' . phpversion();

        // Conversion du tableau de headers en chaîne de caractères
        $headersString = implode("\r\n", $headers);

        // Envoi du mail
        return mail($to, $subject, $message, $headersString);
    }
}