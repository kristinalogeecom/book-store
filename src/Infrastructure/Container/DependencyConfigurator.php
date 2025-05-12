<?php

namespace BookStore\Infrastructure\Container;

use BookStore\Application\BusinessLogic\ServiceInterfaces\AuthorServiceInterface;
use BookStore\Application\BusinessLogic\ServiceInterfaces\BookServiceInterface;
use BookStore\Application\Presentation\Controller\AuthorController;
use BookStore\Application\Presentation\Controller\BookController;
use BookStore\Application\Presentation\Controller\ErrorController;
use BookStore\Application\BusinessLogic\RepositoryInterfaces\AuthorRepositoryInterface;
use BookStore\Application\BusinessLogic\RepositoryInterfaces\BookRepositoryInterface;
use BookStore\Application\Persistence\MySQL\AuthorRepository;
use BookStore\Application\Persistence\MySQL\BookRepository;
use BookStore\Application\Persistence\Session\AuthorRepositorySession;
use BookStore\Application\Persistence\Session\BookRepositorySession;
use BookStore\Application\BusinessLogic\Service\AuthorService;
use BookStore\Application\BusinessLogic\Service\BookService;

class DependencyConfigurator
{
    /**
     * @return void
     * @throws \Exception
     */
    public static function configure(): void
    {
        ServiceRegistry::set(AuthorRepositoryInterface::class, new AuthorRepository);

        ServiceRegistry::set(BookRepositoryInterface::class, new BookRepository);

        ServiceRegistry::set(AuthorServiceInterface::class, new AuthorService(
            ServiceRegistry::get(AuthorRepositoryInterface::class)));

        ServiceRegistry::set(BookServiceInterface::class, new BookService(
            ServiceRegistry::get(BookRepositoryInterface::class)));

        ServiceRegistry::set(AuthorController::class, new AuthorController(
            ServiceRegistry::get(AuthorServiceInterface::class)));

        ServiceRegistry::set(BookController::class, new BookController(
            ServiceRegistry::get(BookServiceInterface::class)));

        ServiceRegistry::set(ErrorController::class, new ErrorController());
    }
}