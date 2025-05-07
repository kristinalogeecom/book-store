<?php

namespace BookStore\Infrastructure;

use BookStore\Repository\AuthorRepositoryInterface;
use BookStore\Repository\BookRepositoryInterface;
use BookStore\Service\AuthorService;
use BookStore\Service\BookService;
use BookStore\Controller\AuthorController;
use BookStore\Controller\BookController;

class ServiceRegistry
{
    private array $services = [];
    private Factory $factory;

    public function __construct() {
        $this->factory = new Factory();
    }

    /**
     * Initialize all services.
     *
     * @return void
     */
    public function initializeServices(): void
    {
        $authorRepository = $this->factory->createAuthorRepository('db');
        $this->set(AuthorRepositoryInterface::class, $authorRepository);

        $authorService = $this->factory->createAuthorService($authorRepository);
        $this->set(AuthorService::class, $authorService);

        $authorController = $this->factory->createAuthorController($authorService);
        $this->set(AuthorController::class, $authorController);

        $bookRepository = $this->factory->createBookRepository('session');
        $this->set(BookRepositoryInterface::class, $bookRepository);

        $bookService = $this->factory->createBookService($bookRepository);
        $this->set(BookService::class, $bookService);

        $bookController = $this->factory->createBookController($bookService);
        $this->set(BookController::class, $bookController);
    }

    /**
     * Registers the service in the registry.
     *
     * @param string $key
     * @param object $service
     * @return void
     */
    public function  set(string $key, object $service): void
    {
        $this->services[$key] = $service;
    }

    /**
     * Checking if the services are initialized
     * Returns the service from the registry
     *
     * @param string $key
     * @return object
     */
    public function get(string $key): object
    {
//        if (!isset($this->services[$key])) {
//            $this->initialize_services();
//        }

        return $this->services[$key];
    }

}