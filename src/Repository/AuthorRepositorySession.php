<?php

namespace BookStore\Repository;

use Exception;

class AuthorRepositorySession implements AuthorRepositoryInterface
{
    public function __construct()
    {
        if(!isset($_SESSION['authors'])) {
            $_SESSION['authors'] = [];
        }
    }

    /**
     * Get all authors from session.
     *
     * @return array
     */
    public function getAllAuthors(): array
    {
        return array_values($_SESSION['authors']);
    }

    /**
     * Create a new author.
     *
     * @param string $firstName
     * @param string $lastName
     * @return void
     */
    public function createAuthor(string $firstName, string $lastName): void
    {
        $id = $this->generateNextId();
        $_SESSION['authors'][$id] = [
            'id' => $id,
            'first_name' => $firstName,
            'last_name' => $lastName,
        ];
    }

    /**
     * Edit an existing author.
     *
     * @param int $authorId
     * @param string $firstName
     * @param string $lastName
     * @return void
     * @throws Exception
     */
    public function editAuthor(int $authorId, string $firstName, string $lastName): void
    {
        if(!isset($_SESSION['authors'][$authorId])) {
            throw new Exception('Author not found');
        }

        $_SESSION['authors'][$authorId]['first_name'] = $firstName;
        $_SESSION['authors'][$authorId]['last_name'] = $lastName;
    }

    /**
     * Delete an author by ID.
     *
     * @param int $authorId
     * @return void
     * @throws Exception
     */
    public function deleteAuthor(int $authorId): void
    {
        if(!isset($_SESSION['authors'][$authorId])) {
            throw new Exception('Author not found');
        }

        unset($_SESSION['authors'][$authorId]);
    }

    /**
     * Get an author by ID.
     *
     * @param int $authorId
     * @return array|null
     */
    public function getAuthorById(int $authorId): ?array
    {
        return $_SESSION['authors'][$authorId] ?? null;
    }

    /**
     * Generate next ID.
     *
     * @return int
     */
    private function generateNextId(): int
    {
        if(empty($_SESSION['authors'])) {
            return 1;
        }

        return max(array_keys($_SESSION['authors'])) + 1;
    }
}
