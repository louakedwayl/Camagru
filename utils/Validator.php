<?php
declare(strict_types=1);

class Validator
{
    public static function validateEmail(string $email): array
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['valid' => false, 'error' => 'invalid_email'];
        }
        return ['valid' => true];
    }
    
    public static function validatePassword(string $password): array
    {
        if (strlen($password) < 6) {
            return ['valid' => false, 'error' => 'password_too_short'];
        }
        if (!preg_match('/[A-Z]/', $password)) {
            return ['valid' => false, 'error' => 'password_no_uppercase'];
        }
        if (preg_match('/\p{Extended_Pictographic}/u', $password))
        {
            return ['valid' => false, 'error' => 'password_emoji'];
        }
        return ['valid' => true];
    }
    
    public static function validateFullname(string $fullname): array
    {
        if (strlen($fullname) < 2 || strlen($fullname) > 50) {
            return ['valid' => false, 'error' => 'fullname_invalid_length'];
        }
        if (!preg_match('/^[a-zA-ZÀ-ÿ\s\'\-]+$/u', $fullname)) {
            return ['valid' => false, 'error' => 'fullname_invalid_chars'];
        }
        return ['valid' => true];
    }
    
    public static function validateUsername(string $username): array
    {
        if (strlen($username) < 3 || strlen($username) > 30) {
            return ['valid' => false, 'error' => 'username_invalid_length'];
        }
        if (!preg_match('/^[a-zA-ZÀ-ÿ0-9_.]+$/u', $username)) {
            return ['valid' => false, 'error' => 'username_invalid_chars'];
        }
        return ['valid' => true];
    }
}