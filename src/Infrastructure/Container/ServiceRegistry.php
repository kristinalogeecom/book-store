<?php

namespace BookStore\Infrastructure\Container;

use BookStore\Application\Presentation\Controller\AuthorController;
use BookStore\Application\Presentation\Controller\BookController;
use BookStore\Application\BusinessLogic\RepositoryInterfaces\AuthorRepositoryInterface;
use BookStore\Application\BusinessLogic\RepositoryInterfaces\BookRepositoryInterface;
use BookStore\Application\Persistence\MySQL\AuthorRepository;
use BookStore\Application\Persistence\MySQL\BookRepository;
use BookStore\Application\Persistence\Session\AuthorRepositorySession;
use BookStore\Application\Persistence\Session\BookRepositorySession;
use BookStore\Application\BusinessLogic\Service\AuthorService;
use BookStore\Application\BusinessLogic\Service\BookService;

class ServiceRegistry
{
    private static array $services = [];

    /**
     * Initializes the services
     *
     * @return void
     * @throws \Exception
     */
    public static function initializeServices(): void
    {
        self::set(AuthorRepositoryInterface::class, new AuthorRepository);

        self::set(BookRepositoryInterface::class, new BookRepository);

        self::set(AuthorService::class, new AuthorService(self::get(AuthorRepositoryInterface::class)));

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