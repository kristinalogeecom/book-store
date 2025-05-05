<?php

namespace BookStore\Infrastructure;

use BookStore\Database\DatabaseConnection;
use BookStore\Repository\AuthorRepositoryInterface;
use BookStore\Repository\AuthorRepository;
use BookStore\Repository\AuthorRepositorySession;
use BookStore\Service\AuthorService;
use BookStore\Controller\AuthorController;

class Factory
{


    /**
     * @param string $type
     * @return AuthorRepositoryInterface
     */
    public function create_author_repository(string $type = 'session'): AuthorRepositoryInterface
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
    public function create_author_service(AuthorRepositoryInterface $repository): AuthorService
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