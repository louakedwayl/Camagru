<?php

class Mailer
{
    private static $from = "no-reply@camagru.com";

    /**
     * Envoie le CODE de validation (6 chiffres)
     */
    public static function sendValidationCode(string $to, string $username, string $code): bool
    {
        $subject = "Votre code de validation Camagru";
        
        // CSS Inline pour faire joli dans les boîtes mail
        $message = "
        <html>
        <head>
            <title>Validation Camagru</title>
            <style>
                .container { font-family: Arial, sans-serif; padding: 20px; color: #333; }
                .code-box { 
                    background-color: #f4f4f4; 
                    border: 1px solid #ddd; 
                    font-size: 24px; 
                    font-weight: bold; 
                    letter-spacing: 5px; 
                    padding: 15px; 
                    text-align: center; 
                    width: 200px; 
                    margin: 20px 0;
                    border-radius: 5px;
                }
                .footer { font-size: 12px; color: #777; margin-top: 30px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <h2>Bienvenue sur Camagru, " . htmlspecialchars($username) . " !</h2>
                <p>Merci de vous être inscrit.</p>
                <p>Voici votre code de confirmation à 6 chiffres :</p>
                
                <div class='code-box'>" . htmlspecialchars($code) . "</div>
                
                <p>Copiez ce code et collez-le dans la page de validation pour activer votre compte.</p>
                <p>Ce code expirera dans 10 minutes.</p>
                
                <div class='footer'>
                    Ceci est un mail automatique, merci de ne pas répondre.
                </div>
            </div>
        </body>
        </html>
        ";

        return self::send($to, $subject, $message);
    }

    private static function send(string $to, string $subject, string $message): bool
    {
        $headers = [];
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-Type: text/html; charset=utf-8';
        $headers[] = 'From: Camagru <' . self::$from . '>';
        $headers[] = 'Reply-To: ' . self::$from;
        $headers[] = 'X-Mailer: PHP/' . phpversion();

        $headersString = implode("\r\n", $headers);

        return mail($to, $subject, $message, $headersString);
    }
}