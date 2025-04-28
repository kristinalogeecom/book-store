<?php

namespace Service;

use Exception;
use Repository\AuthorRepository;
session_start();

class AuthorService
{
    private AuthorRepository $repository;

    public function __construct(AuthorRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAuthors(): array
    {
        return $this -> repository -> getAllAuthors();
    }

    /**
     * @throws Exception
     */
    public function createAuthor(string $first_name, string $last_name): void
    {
        $errors = $this->validateAuthorData($first_name, $last_name);

        if (!empty($errors)) {
            throw new Exception(json_encode($errors));
        }

        $this -> repository -> createAuthor($first_name, $last_name);
    }

    /**
     * @throws Exception
     */
    public function editAuthor(int $authorId, string $first_name, string $last_name): void
    {
        $errors = $this->validateAuthorData($first_name, $last_name);

        if (!empty($errors)) {
            throw new Exception(json_encode($errors));
        }

        $this -> repository -> editAuthor($authorId, $first_name, $last_name);
    }

    private function validateAuthorData(string $first_name, string $last_name): array
    {
        $errors = [];

        if (empty($first_name)) {
            $errors['first_name'] = 'First name is required';
        }

        if (empty($last_name)) {
            $errors['last_name'] = 'Last name is required';
        }

        if (strlen($first_name) > 100) {
            $errors['first_name'] = 'First name cannot exceed 100 characters.';
        }

        if (strlen($last_name) > 100) {
            $errors['last_name'] = 'Last name cannot exceed 100 characters.';
        }

        return $errors;
    }

    /**
     * @throws Exception
     */
    public function getAuthorById(int $id)
    {
        return $this->repository->getAuthorById($id);
    }

    /**
     * @throws Exception
     */
    public function deleteAuthor(int $id): void
    {
        $this -> repository -> deleteAuthor($id);
    }
}
