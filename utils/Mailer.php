<?php

class Mailer
{
    private static $from = "contact.camagru@gmail.com";

    /**
     * Sends the validation CODE (6 digits)
     */
    public static function sendValidationCode(string $to, string $username, string $code): bool
    {
        $subject = $code . " is your Camagru code ";

        $baseUrl = 'http://localhost:8080/index.php'; 

        $queryParams = http_build_query([
            'action' => 'verify_url',
            'email'  => $to,
            'code'   => $code
        ]);

        $verificationLink = $baseUrl . '?' . $queryParams;

        $softGrey = "#737373"; 
        $footerGreyText = "#8e8e8e";
        $linkBlue = "#0056b3"; 
        $bgColor = "#ffffff";
        $footerBgColor = "#fafafa";
        $font = "-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif";

        $message = "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <style>
                * { font-family: $font !important; }
            </style>
        </head>
        <body style='margin: 0; padding: 0; background-color: #f9f9f9; font-family: $font;'>
            
            <table border='0' cellpadding='0' cellspacing='0' width='100%' style='background-color: #f9f9f9; padding: 20px 0;'>
                <tr>
                    <td align='center'>
                        <table border='0' cellpadding='0' cellspacing='0' width='100%' style='max-width: 600px; background-color: $bgColor; border: 1px solid #dbdbdb; border-radius: 3px;'>
                            
                            <tr>
                                <td style='padding: 25px 25px 10px 25px;'>
                                    <table border='0' cellpadding='0' cellspacing='0'>
                                        <tr>
                                            <td style='vertical-align: middle;'>
                                                <img src='https://raw.githubusercontent.com/louakedwayl/Camagru/refs/heads/main/assets/images/icon/Camagru_icon_black.png' 
                                                    width='45' height='45' style='display: block; object-fit: contain;'>
                                            </td>
                                            <td style='vertical-align: middle; padding-left: 10px;'>
                                                <img src='https://raw.githubusercontent.com/louakedwayl/Camagru/refs/heads/main/assets/images/logo.png' 
                                                    width='150' style='display: block; margin-top: 9px;'>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <tr>
                                <td style='padding: 20px 30px; line-height: 1.6;'>
                                    <p style='font-size: 17px; margin-bottom: 15px; font-weight: 500; color: $softGrey;'>Hi " . htmlspecialchars($username) . ",</p>
                                    
                                    <p style='font-size: 16px; color: $softGrey; margin-bottom: 25px;'>
                                        Someone tried to sign up for a Camagru account with the email address " . htmlspecialchars($to) . ". 
                                        If it was you, please enter this confirmation code in the app to verify your email address:
                                    </p>

                                    <div style='font-size: 44px; font-weight: 400; color: $softGrey; margin-top: 25px; margin-bottom: 18px; text-align: center; letter-spacing: 5px;'>
                                        " . htmlspecialchars($code) . "
                                    </div>

                                    <div style='text-align: center; margin-bottom: 35px;'>
                                        <a href='" . htmlspecialchars($verificationLink) . "' 
                                           style='color: $linkBlue; text-decoration: none; font-size: 15px; font-weight: 600;'>
                                            Click here to confirm your email address
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td style='padding: 20px 30px; background-color: $footerBgColor; border-top: 1px solid #efefef; border-radius: 0 0 3px 3px;'>
                                    <p style='font-size: 12px; color: $footerGreyText; margin-bottom: 8px;'>If you didn't request this code, you can safely ignore this email.</p>
                                    <p style='font-size: 12px; color: $footerGreyText; margin: 0;'>© 2025 Wayl Louaked. Licensed under 
                                        <a href='https://opensource.org/licenses/MIT' target='_blank' style='color: $linkBlue; text-decoration: none;'>MIT License</a>.
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

        </body>
        </html>
        ";

        return self::send($to, $subject, $message);
    }


/**
 * Sends the password RESET link (styled as a text link like the validation mail)
 */
public static function sendResetLink(string $to, string $username, string $code): bool
{
    $subject = $username . ", we've made it easy to get back on Camagru";

    $baseUrl = 'http://localhost:8080/index.php'; 

    $queryParams = http_build_query([
        'action' => 'password_reset_confirm',
        'email'  => $to,
        'code'   => $code
    ]);

    $resetLink = $baseUrl . '?' . $queryParams;

    // Constantes visuelles
    $softGrey = "#737373"; 
    $footerGreyText = "#8e8e8e";
    $linkBlue = "#0056b3"; 
    $bgColor = "#ffffff";
    $footerBgColor = "#fafafa";
    $font = "-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif";

    $message = "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <style>
            * { font-family: $font !important; }
        </style>
    </head>
    <body style='margin: 0; padding: 0; background-color: #f9f9f9; font-family: $font;'>
        
        <table border='0' cellpadding='0' cellspacing='0' width='100%' style='background-color: #f9f9f9; padding: 20px 0;'>
            <tr>
                <td align='center'>
                    <table border='0' cellpadding='0' cellspacing='0' width='100%' style='max-width: 600px; background-color: $bgColor; border: 1px solid #dbdbdb; border-radius: 3px;'>
                        
                        <tr>
                            <td style='padding: 25px 25px 10px 25px;'>
                                <table border='0' cellpadding='0' cellspacing='0'>
                                    <tr>
                                        <td style='vertical-align: middle;'>
                                            <img src='https://raw.githubusercontent.com/louakedwayl/Camagru/refs/heads/main/assets/images/icon/Camagru_icon_black.png' 
                                                width='45' height='45' style='display: block; object-fit: contain;'>
                                        </td>
                                        <td style='vertical-align: middle; padding-left: 10px;'>
                                            <img src='https://raw.githubusercontent.com/louakedwayl/Camagru/refs/heads/main/assets/images/logo.png' 
                                                width='150' style='display: block; margin-top: 9px;'>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <tr>
                            <td style='padding: 20px 30px; line-height: 1.6;'>
                                <p style='font-size: 17px; margin-bottom: 15px; font-weight: 500; color: $softGrey;'>Hi " . htmlspecialchars($username) . ",</p>
                                
                                <p style='font-size: 16px; color: $softGrey; margin-bottom: 25px;'>
                                    We're sorry you're having trouble logging into Camagru. 
                                    We got a message that you forgot your password. If this was you, 
                                    you can get back into your account by clicking the link below :
                                </p>

                                <div style='text-align: center; margin-top: 30px; margin-bottom: 35px;'>
                                    <a href='" . htmlspecialchars($resetLink) . "' 
                                       style='color: $linkBlue; text-decoration: none; font-size: 15px; font-weight: 600;'>
                                        Reset your password
                                    </a>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td style='padding: 20px 30px; background-color: $footerBgColor; border-top: 1px solid #efefef; border-radius: 0 0 3px 3px;'>
                                <p style='font-size: 12px; color: $footerGreyText; margin-bottom: 8px;'>If you didn't request a password reset, you can safely ignore this email.</p>
                                <p style='font-size: 12px; color: $footerGreyText; margin: 0;'>© 2025 Wayl Louaked. Licensed under 
                                    <a href='https://opensource.org/licenses/MIT' target='_blank' style='color: $linkBlue; text-decoration: none;'>MIT License</a>.
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

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