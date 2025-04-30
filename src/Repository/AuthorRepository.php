<?php

namespace BookStore\Repository;

use PDO;
use Exception;

class AuthorRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Get all authors from session.
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
     * @param $first_name
     * @param $last_name
     * @return void
     */
    public function createAuthor($first_name, $last_name): void
    {
        $query = "INSERT INTO authors(first_name, last_name) VALUES(?, ?)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$first_name, $last_name]);
    }

    /**
     * Edit an existing author.
     *
     * @param int $authorId Author ID
     * @param string $first_name
     * @param string $last_name
     * @return void
     * @throws Exception
     */
    public function editAuthor(int $authorId, string $first_name, string $last_name): void
    {
        $query = "UPDATE authors SET first_name = ?, last_name = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$first_name, $last_name, $authorId]);

        if ($stmt->rowCount() === 0) {
            throw new Exception('Author not found.');
        }
    }

    /**
     * Get an author by ID.
     *
     * @param int $id Author ID
     * @return array|null Author data or null if not found
     */
    public function getAuthorById(int $id): ?array
    {
        $query = "SELECT * FROM authors WHERE id = ?";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$id]);
        $author = $stmt->fetch();

        return $author ?: null;
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
        $query = "DELETE FROM authors WHERE id = ?";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$id]);

        if ($stmt->rowCount() === 0) {
            throw new Exception('Author not found.');
        }
    }

}
