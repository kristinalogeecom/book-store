<?php

namespace BookStore\Infrastructure;

use BookStore\Database\DatabaseConnection;
use BookStore\Repository\AuthorRepository;
use BookStore\Repository\BookRepository;
use BookStore\Repository\AuthorRepositoryInterface;
use BookStore\Repository\BookRepositoryInterface;
use BookStore\Repository\BookRepositorySession;
use BookStore\Service\AuthorService;
use BookStore\Service\BookService;
use BookStore\Controller\AuthorController;
use BookStore\Controller\BookController;

class ServiceRegistry
{
    private static array $services = [];

    /**
     * Initialize all services.
     *
     * @return void
     * @throws \Exception
     */
    public static function initializeServices(): void
    {
        $pdo = DatabaseConnection::connect();
        self::set(AuthorRepositoryInterface::class, new AuthorRepository($pdo));
        self::set(BookRepositoryInterface::class, new BookRepository($pdo));

        self::set(AuthorService::class, new AuthorService(self::get(AuthorRepositoryInterface::class), self::get(BookRepositoryInterface::class)));

        self::set(BookService::class, new BookService(self::get(BookRepositoryInterface::class)));

        self::set(AuthorController::class, new AuthorController(self::get(AuthorService::class)));

        self::set(BookController::class, new BookController(self::get(BookService::class)));
    }

    /**
     * Registers the service in the registry.
     *
     * @param string $key
     * @param object $service
     *
     * @return void
     */
    public static function set(string $key, object $service): void
    {
        self::$services[$key] = $service;
    }

    /**
     * Checking if the services are initialized
     * Returns the service from the registry
     *
     * @param string $key
     *
     * @return object
     * @throws \Exception
     */
    public static function get(string $key): object
    {
        if (!isset(self::$services[$key])) {
            throw new \Exception("Service '{$key}' not found");
        }

        return self::$services[$key];
    }

}