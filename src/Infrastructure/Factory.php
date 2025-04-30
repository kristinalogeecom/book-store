<?php

namespace BookStore\Infrastructure;

use BookStore\Database\DatabaseConnection;
use BookStore\Repository\AuthorRepository;
use BookStore\Service\AuthorService;
use BookStore\Controller\AuthorController;

class Factory
{
    /**
     * Create an instance of AuthorRepository.
     *
     * @return AuthorRepository
     */
    public function createAuthorRepository(): AuthorRepository
    {
        $pdo = DatabaseConnection::connect();
        return new AuthorRepository($pdo);
    }

    /**
     * Create an instance of AuthorService.
     *
     * @param AuthorRepository $repository
     * @return AuthorService
     */
    public function createAuthorService(AuthorRepository $repository): AuthorService
    {

        return new AuthorService($repository);
    }

    /**
     * Create an instance of AuthorController.
     *
     * @param AuthorService $authorService
     * @return AuthorController
     */
    public function createAuthorController(AuthorService $authorService): AuthorController
    {
        return new AuthorController($authorService);
    }
}