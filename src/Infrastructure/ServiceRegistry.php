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
    public function initialize_services(): void
    {
        $author_repository = $this->factory->create_author_repository();
        $this->set(AuthorRepository::class, $author_repository);

        $author_service = $this->factory->create_author_service($author_repository);
        $this->set(AuthorService::class, $author_service);

        $author_controller = $this->factory->create_author_controller($author_service);
        $this->set(AuthorController::class, $author_controller);
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
            $this->initialize_services();
        }

        return $this->services[$key];
    }

}