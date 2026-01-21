<?php
if (ob_get_length()) ob_clean();

$to = "louakedwayl@protonmail.com";
$code = "424-242";
$verificationLink = "http://localhost:8080/index.php?action=verify&code=" . $code;

$textGrisDoux = "#737373"; 
$textGrisFooter = "#8e8e8e";
$linkBlueIntermediaire = "#0056b3"; 
$bgColor = "#ffffff";
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
                                    <td width='45' style='vertical-align: middle;'>
                                        <img src='https://raw.githubusercontent.com/louakedwayl/Camagru/refs/heads/main/assets/images/icon/Camagru_icon_black.png' 
                                            width='45' height='45' style='display: block; object-fit: contain; margin-right: 5px;'>
                                    </td>
                                    <td style='padding: 0 12px;'>
                                        <div style='width: 1px; height: 30px; background-color: #dbdbdb;'></div>
                                    </td>
                                    <td style='vertical-align: middle; padding-left: 0;'>
                                        <img src='https://raw.githubusercontent.com/louakedwayl/Camagru/refs/heads/main/assets/images/logo.png' 
                                            width='150' style='display: block; margin-left: -18px;'>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style='padding: 20px 30px; line-height: 1.6;'>
                            <p style='font-size: 17px; margin-bottom: 15px; font-weight: 500; color: $textGrisDoux;'>Hi,</p>
                            
                            <p style='font-size: 16px; color: $textGrisDoux; margin-bottom: 25px;'>
                                Someone tried to sign up for a Camagru account with the email address " . htmlspecialchars($to) . ". 
                                If it was you, please enter this confirmation code in the app to verify your email address:
                            </p>

                            <div style='font-size: 44px; font-weight: 400; color: $textGrisDoux; margin-top: 25px; margin-bottom: 18px; text-align: center; letter-spacing: 5px;'>
                                " . htmlspecialchars($code) . "
                            </div>

                            <div style='text-align: center; margin-bottom: 35px;'>
                                <a href='" . htmlspecialchars($verificationLink) . "' 
                                   style='color: $linkBlueIntermediaire; text-decoration: none; font-size: 15px; font-weight: 600;'>
                                    Click here to confirm your email address
                                </a>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td style='padding: 20px 30px; background-color: $bgColor; border-top: 1px solid #efefef; border-radius: 0 0 3px 3px;'>
                            <p style='font-size: 12px; color: $textGrisFooter; margin-bottom: 8px;'>If you didn't request this code, you can safely ignore this email.</p>
                            <p style='font-size: 12px; color: $textGrisFooter; margin: 0;'>© 2025 Wayl Louaked. Licensed under 
                                <a href='https://opensource.org/licenses/MIT' target='_blank' style='color: $linkBlueIntermediaire; text-decoration: none;'>MIT License</a>.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>
</html>";

echo $message;
exit;
?>