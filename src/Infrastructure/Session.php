<?php

namespace BookStore\Infrastructure;

class Session
{
    private static ?self $instance = null;

    private function __construct()
    {
        if(session_status() !== PHP_SESSION_ACTIVE){
            session_start();
        }
    }

    public static function getInstance(): Session
    {
        if(self::$instance === null){
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function unset(string $key): void
    {
        unset($_SESSION[$key]);
    }
}