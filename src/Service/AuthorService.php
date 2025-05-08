<?php

namespace BookStore\Service;

use BookStore\Repository\BookRepositoryInterface;
use BookStore\Repository\AuthorRepositoryInterface;
use BookStore\Models\Author;
use Exception;


class AuthorService
{
    /**
     * @var AuthorRepositoryInterface
     */
    private AuthorRepositoryInterface $repository;
    private BookRepositoryInterface $bookRepository;


    /**
     * @param AuthorRepositoryInterface $repository
     * @param BookRepositoryInterface $bookRepository
     */
    public function __construct(AuthorRepositoryInterface $repository, BookRepositoryInterface $bookRepository)
    {
        $this->repository = $repository;
        $this->bookRepository = $bookRepository;
    }

    /**
     * Get all authors.
     *
     * @return array
     */
    public function getAuthors(): array
    {
        $authors = $this->repository->getAllAuthors();
        foreach ($authors as $author) {
            $author->setBookCount($this->bookRepository->countByAuthorId($author->getId()));
        }
        return $authors;
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
        $errors = $this->validateAuthorData($author->getFirstName(), $author->getLastName());

        if (!empty($errors)) {
            throw new Exception(json_encode($errors));
        }

        $this->repository->createAuthor($author);
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
        $errors = $this->validateAuthorData($author->getFirstName(), $author->getLastName());

        if (!empty($errors)) {
            throw new Exception(json_encode($errors));
        }

        $this->repository->editAuthor($author);
    }

    /**
     * Get an author by ID.
     *
     * @param int $id Author ID
     * @return array|null Author data or null if not found
     */
    public function getAuthorById(int $id): ?Author
    {
        return $this->repository->getAuthorById($id);
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
        $this->repository->deleteAuthor($id);
    }

    /**
     * Validate author data.
     *
     * @param string $firstName
     * @param string $lastName
     * @return array Validation errors
     */
    private function validateAuthorData(string $firstName, string $lastName): array
    {
        $errors = [];

        if (empty($firstName)) {
            $errors['first_name'] = 'First name is required';
        }

        if (empty($lastName)) {
            $errors['last_name'] = 'Last name is required';
        }

        if (strlen($firstName) > 100) {
            $errors['first_name'] = 'First name cannot exceed 100 characters.';
        }

        if (strlen($lastName) > 100) {
            $errors['last_name'] = 'Last name cannot exceed 100 characters.';
        }

        return $errors;
    }
}
