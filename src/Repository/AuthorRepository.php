<?php

namespace BookStore\Repository;

use BookStore\Models\Author;
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
     * @return Author[]
     */
    public function getAllAuthors(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM authors");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $authors = [];
        foreach ($rows as $row) {
            $authors[] = new Author(
                (int)$row['id'],
                $row['first_name'],
                $row['last_name']
            );
        }
        return $authors;
    }

    /**
     * Create a new author.
     *
     * @param Author $author
     * @return void
     */
    public function createAuthor(Author $author): void
    {
        $query = "INSERT INTO authors(first_name, last_name) VALUES(?, ?)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            $author->getFirstName(),
            $author->getLastName()
        ]);
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
        $query = "UPDATE authors SET first_name = ?, last_name = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$author->getFirstName(),
            $author->getLastName(),
            $author->getId()
        ]);

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
     * @return ?Author Author data or null if not found
     */
    public function getAuthorById(int $authorId): ?Author
    {
        $query = "SELECT * FROM authors WHERE id = ?";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$authorId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? new Author(
            (int)$row['id'],
            $row['first_name'],
            $row['last_name']
        ) : null;
    }

}
