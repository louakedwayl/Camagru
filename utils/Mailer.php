<?php

class Mailer
{
    private static $from = "no-reply@camagru.com";

    /**
     * Envoie le CODE de validation (6 chiffres)
     */
    public static function sendValidationCode(string $to, string $username, string $code): bool
    {
        $subject = "Camagru Verification Code"; // Titre en Anglais

        // 👇 ATTENTION : Assure-toi que ce port est le bon (8080 ou 8000 ?)
        $baseUrl = 'http://localhost:8080/index.php'; 

        $queryParams = http_build_query([
            'action' => 'verify_url',
            'email'  => $to,
            'code'   => $code
        ]);

        $verificationLink = $baseUrl . '?' . $queryParams;

        $message = "
        <html>
        <head>
            <title>Camagru Verification</title>
            <style>
                .container { font-family: Arial, sans-serif; padding: 20px; color: #333; max-width: 600px; margin: 0 auto; }
                .code-box { 
                    background-color: #f4f4f4; 
                    border: 1px solid #ddd; 
                    font-size: 24px; 
                    font-weight: bold; 
                    letter-spacing: 5px; 
                    padding: 15px; 
                    text-align: center; 
                    width: 200px; 
                    margin: 20px auto;
                    border-radius: 5px;
                }
                .btn-link {
                    display: block;
                    width: 200px;
                    margin: 20px auto;
                    padding: 15px;
                    background-color: #007BFF;
                    color: #ffffff !important;
                    text-decoration: none;
                    text-align: center;
                    border-radius: 5px;
                    font-weight: bold;
                }
                .footer { font-size: 12px; color: #777; margin-top: 30px; text-align: center; }
            </style>
        </head>
        <body>
            <div class='container'>
                <h2>Welcome to Camagru, " . htmlspecialchars($username) . "!</h2>
                <p>Thanks for signing up. To activate your account, you have two options:</p>
                
                <h3>Option 1: Click the button</h3>
                <a href='" . htmlspecialchars($verificationLink) . "' class='btn-link'>Verify My Account</a>

                <h3>Option 2: Enter this code manually</h3>
                <div class='code-box'>" . htmlspecialchars($code) . "</div>
                
                <p>This link and code will expire in 10 minutes.</p>
                
                <div class='footer'>
                    If the button doesn't work, copy this link:<br>
                    <a href='" . htmlspecialchars($verificationLink) . "'>" . htmlspecialchars($verificationLink) . "</a>
                    <br><br>
                    This is an automated email, please do not reply.
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