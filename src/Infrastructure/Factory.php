<?php

namespace BookStore\Infrastructure;

use BookStore\Database\DatabaseConnection;
use BookStore\Repository\AuthorRepositoryInterface;
use BookStore\Repository\AuthorRepository;
use BookStore\Repository\AuthorRepositorySession;
use BookStore\Repository\BookRepositoryInterface;
use BookStore\Repository\BookRepositorySession;
use BookStore\Service\AuthorService;
use BookStore\Service\BookService;
use BookStore\Controller\AuthorController;
use BookStore\Controller\BookController;

class Factory
{


    /**
     * @param string $type
     * @return AuthorRepositoryInterface
     */
    public function createAuthorRepository(string $type = 'session'): AuthorRepositoryInterface
    {
        if($type === 'session') {
            return new AuthorRepositorySession();
        } else if ($type === 'db') {
            $pdo = DatabaseConnection::connect();
            return new AuthorRepository($pdo);
        }

        throw new \InvalidArgumentException("Unknown repository type: $type");
    }

    /**
     * Create an instance of AuthorService.
     *
     * @param AuthorRepository $repository
     * @return AuthorService
     */
    public function createAuthorService(AuthorRepositoryInterface $repository): AuthorService
    {
        return new AuthorService($repository);
    }

    /**
     * Create an instance of AuthorController.
     *
     * @param AuthorService $author_service
     * @return AuthorController
     */
    public function createAuthorController(AuthorService $author_service): AuthorController
    {
        return new AuthorController($author_service);
    }

    public function createBookRepository(string $type = 'session'): BookRepositoryInterface
    {
        // Za sada imamo samo session-based repo za knjige
        if ($type === 'session') {
            return new BookRepositorySession();
        }

        // Ako kasnije budeš pravio BookRepository za bazu podataka
        throw new \InvalidArgumentException("Unknown book repository type: $type");
    }

    public function createBookService(BookRepositoryInterface $repository): BookService
    {
        return new BookService($repository);
    }

    public function createBookController(BookService $service): BookController
    {
        return new BookController($service);
    }
}