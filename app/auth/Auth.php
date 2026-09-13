<?php

class Auth
{
    public static function startSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function isLoggedIn()
    {
        self::startSession();

        return isset($_SESSION["user_id"]);
    }

    public static function getRole()
    {
        self::startSession();

        return $_SESSION["role"] ?? null;
    }

    public static function requireLogin()
    {
        if (!self::isLoggedIn()) {
            header("Location: /hospital_management/public/login.php");
            exit;
        }
    }

    public static function requireRole($role)
    {
        self::requireLogin();

        if (self::getRole() !== $role) {
            http_response_code(403);

            echo "Access denied.";
            exit;
        }
    }

    public static function logout()
    {
        self::startSession();

        session_unset();
        session_destroy();
    }
}

?>
