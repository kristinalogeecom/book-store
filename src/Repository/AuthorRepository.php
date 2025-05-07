<?php

namespace BookStore\Repository;

use PDO;
use Exception;

class AuthorRepository implements AuthorRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Get all authors from database.
     *
     * @return array List of authors
     */
    public function getAllAuthors(): array
    {
        $query = "SELECT * FROM authors";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Create a new author.
     *
     * @param $firstName
     * @param $lastName
     * @return void
     */
    public function createAuthor($firstName, $lastName): void
    {
        $query = "INSERT INTO authors(first_name, last_name) VALUES(?, ?)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$firstName, $lastName]);
    }

    /**
     * Edit an existing author.
     *
     * @param int $authorId Author ID
     * @param string $firstName
     * @param string $lastName
     * @return void
     * @throws Exception
     */
    public function editAuthor(int $authorId, string $firstName, string $lastName): void
    {
        $query = "UPDATE authors SET first_name = ?, last_name = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$firstName, $lastName, $authorId]);

        if ($stmt->rowCount() === 0) {
            throw new Exception('Author not found.');
        }
    }

    /**
     * Delete an author by ID.
     *
     * @param int $authorId Author ID
     * @return void
     * @throws Exception
     */
    public function deleteAuthor(int $authorId): void
    {
        $query = "DELETE FROM authors WHERE id = ?";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$authorId]);

        if ($stmt->rowCount() === 0) {
            throw new Exception('Author not found.');
        }
    }

    /**
     * Get an author by ID.
     *
     * @param int $authorId Author ID
     * @return array|null Author data or null if not found
     */
    public function getAuthorById(int $authorId): ?array
    {
        $query = "SELECT * FROM authors WHERE id = ?";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$authorId]);
        $author = $stmt->fetch();

        return $author ?: null;
    }

}
