<?php

namespace BookStore\Repository;

use BookStore\Database\DatabaseConnection;
use BookStore\Models\Author;
use PDO;
use Exception;

class AuthorRepository implements AuthorRepositoryInterface
{

    /**
     * Get all authors from database.
     *
     * @return Author[]
     */
    public function getAllAuthors(): array
    {
        $sql = "SELECT a.id, a.first_name, a.last_name, COUNT(b.id) AS book_count
FROM authors a 
LEFT JOIN books b ON a.id = b.author_id
GROUP BY a.id, a.first_name, a.last_name";

        $stmt = DatabaseConnection::connect()->query($sql);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $authors = [];
        foreach ($rows as $row) {
            $author = new Author(
                (int)$row['id'],
                $row['first_name'],
                $row['last_name']
            );
            $author->setBookCount((int)$row['book_count']);
            $authors[] = $author;
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
        $stmt = DatabaseConnection::connect()->prepare($query);
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
        $stmt = DatabaseConnection::connect()->prepare($query);
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
        $stmt = DatabaseConnection::connect()->prepare($query);
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
        $stmt = DatabaseConnection::connect()->prepare($query);
        $stmt->execute([$authorId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? new Author(
            (int)$row['id'],
            $row['first_name'],
            $row['last_name']
        ) : null;
    }

}
