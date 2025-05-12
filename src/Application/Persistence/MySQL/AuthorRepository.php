<?php

namespace BookStore\Application\Persistence\MySQL;

use BookStore\Application\BusinessLogic\RepositoryInterfaces\AuthorRepositoryInterface;
use BookStore\Infrastructure\Database\DatabaseConnection;
use BookStore\Application\BusinessLogic\Models\Author;
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
        $sql = "
            SELECT
                a.id, a.first_name, a.last_name, COUNT(b.id) AS book_count
            FROM 
                authors a LEFT JOIN books b ON a.id = b.author_id
            GROUP BY 
                a.id, a.first_name, a.last_name";

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
     * @return bool
     * @throws Exception
     */
    public function createAuthor(Author $author): bool
    {
        $query = "INSERT INTO authors(first_name, last_name) VALUES(?, ?)";
        $stmt = DatabaseConnection::connect()->prepare($query);
        $success = $stmt->execute([
            $author->getFirstName(),
            $author->getLastName()
        ]);

        if (!$success) {
            throw new Exception("Failed to create author");
        }

        return true;
    }

    /**
     * Edit an existing author.
     *
     * @param Author $author
     * @return bool
     * @throws Exception
     */
    public function editAuthor(Author $author): bool
    {
        $query = "UPDATE authors SET first_name = ?, last_name = ? WHERE id = ?";
        $stmt = DatabaseConnection::connect()->prepare($query);
        $success = $stmt->execute([$author->getFirstName(),
            $author->getLastName(),
            $author->getId()
        ]);

        if (!$success || $stmt->rowCount() === 0) {
            throw new Exception("Failed to update author or author not found.");
        }

        return true;
    }

    /**
     * Delete an author by ID.
     *
     * @param int $authorId Author ID
     * @return bool
     * @throws Exception
     */
    public function deleteAuthor(int $authorId): bool
    {
        try {
            DatabaseConnection::connect()->beginTransaction();

            $queryBooks = "DELETE FROM books WHERE author_id = ?";
            $stmtBooks = DatabaseConnection::connect()->prepare($queryBooks);
            $successBooks = $stmtBooks->execute([$authorId]);

            if (!$successBooks) {
                throw new Exception("Failed to delete author's books.");
            }

            $queryAuthor = "DELETE FROM authors WHERE id = ?";
            $stmtAuthor = DatabaseConnection::connect()->prepare($queryAuthor);
            $successAuthor = $stmtAuthor->execute([$authorId]);

            if (!$successAuthor || $stmtAuthor->rowCount() === 0) {
                throw new Exception("Failed to delete author or author not found.");
            }

            DatabaseConnection::connect()->commit();

            return true;

        } catch (Exception $e) {
            DatabaseConnection::connect()->rollBack();
            throw new Exception('Failed to delete author' . $e->getMessage());
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
