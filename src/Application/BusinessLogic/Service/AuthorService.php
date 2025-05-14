<?php

namespace BookStore\Application\BusinessLogic\Service;

use BookStore\Application\BusinessLogic\Models\Author;
use BookStore\Application\BusinessLogic\ServiceInterfaces\AuthorServiceInterface;
use BookStore\Application\BusinessLogic\RepositoryInterfaces\AuthorRepositoryInterface;
use Exception;

class AuthorService implements AuthorServiceInterface
{
    /**
     * @var AuthorRepositoryInterface
     */
    private AuthorRepositoryInterface $repository;

    /**
     * @param AuthorRepositoryInterface $repository
     */
    public function __construct(AuthorRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get all authors.
     *
     * @return array
     */
    public function getAuthors(): array
    {
        return $this->repository->getAllAuthors();
    }

    /**
     * Create a new author.
     *
     * @param Author $author
     * @return void
     * @throws Exception
     */
    public function createAuthor(Author $author): void
    {
        $this->validateAuthorData($author);

        try {
            $this->repository->createAuthor($author);
        } catch (Exception $e) {
            throw new Exception(json_encode(['general' => $e->getMessage()]));
        }
    }

    /**
     * Edit an existing author.
     *
     * @param Author $author
     * @return void
     * @throws Exception
     */
    public function editAuthor(Author $author): void
    {
        $existingAuthor = $this->getAuthorById($author->getId());

        if ($author->getFirstName() === $existingAuthor->getFirstName() &&
            $author->getLastName() === $existingAuthor->getLastName()) {

            return;
        }

        $this->validateAuthorData($author);

        try {
            $this->repository->editAuthor($author);
        } catch (Exception $e) {
            throw new Exception(json_encode(['general' => $e->getMessage()]));
        }
    }

    /**
     * Delete an author by ID.
     *
     * @param int $id Author ID
     * @return void
     * @throws Exception
     */
    public function deleteAuthor(int $id): void
    {
        $this->getAuthorById($id);

        try {
            $this->repository->deleteAuthor($id);
        } catch (Exception $e) {
            throw new Exception(json_encode(['general' => $e->getMessage()]));
        }
    }

    /**
     * @param int $id
     * @return Author|null
     * @throws Exception
     */
    public function getAuthorById(int $id): ?Author
    {

        $existingAuthor = $this->repository->getAuthorById($id);

        if (!$existingAuthor) {
            throw new \Exception(json_encode(['general' => 'Author not found.']));
        }

        return $existingAuthor;
    }

    /**
     * Validate author data.
     *
     * @param Author $author
     * @return void
     * @throws Exception
     */
    private function validateAuthorData(Author $author): void
    {
        $firstName = $author->getFirstName();
        $lastName = $author->getLastName();
        $errors = [];

        if (empty($firstName)) {
            $errors['firstName'] = 'First name is required';
        }

        if (!empty($firstName) && strlen($firstName) > 100) {
            $errors['firstName'] = 'First name cannot exceed 100 characters.';
        }

        if (empty($lastName)) {
            $errors['lastName'] = 'Last name is required';
        }

        if (!empty($lastName) && strlen($lastName) > 100) {
            $errors['lastName'] = 'Last name cannot exceed 100 characters.';
        }

        if (!empty($errors)) {
            throw new \Exception(json_encode($errors));
        }

    }
}
