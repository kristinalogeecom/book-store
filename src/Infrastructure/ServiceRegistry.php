<?php

namespace BookStore\Infrastructure;

use BookStore\Repository\AuthorRepository;
use BookStore\Service\AuthorService;
use BookStore\Controller\AuthorController;

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
        $authorRepository = $this->factory->createAuthorRepository();
        $this->set(AuthorRepository::class, $authorRepository);

        $authorService = $this->factory->createAuthorService($authorRepository);
        $this->set(AuthorService::class, $authorService);

        $authorController = $this->factory->createAuthorController($authorService);
        $this->set(AuthorController::class, $authorController);
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
        if (!isset($this->services[$key])) {
            $this->initializeServices();
        }
        return $this->services[$key];
    }

}