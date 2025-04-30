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
    public function create_author_repository(): AuthorRepository
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
    public function create_author_service(AuthorRepository $repository): AuthorService
    {
        return new AuthorService($repository);
    }

    /**
     * Create an instance of AuthorController.
     *
     * @param AuthorService $author_service
     * @return AuthorController
     */
    public function create_author_controller(AuthorService $author_service): AuthorController
    {
        return new AuthorController($author_service);
    }
}