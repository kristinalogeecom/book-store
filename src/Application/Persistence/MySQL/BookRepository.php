<?php

namespace BookStore\Application\Persistence\MySQL;

use BookStore\Application\BusinessLogic\RepositoryInterfaces\BookRepositoryInterface;
use BookStore\Infrastructure\Database\DatabaseConnection;
use BookStore\Application\BusinessLogic\Models\Book;
use Exception;
use PDO;

class BookRepository implements BookRepositoryInterface
{

    /**
     * Returns all books by the author.
     *
     * @param int $authorId
     * @return array
     */
    public function getAllBooksForAuthor(int $authorId): array
    {
        $stmt = DatabaseConnection::connect()->prepare("SELECT * FROM books WHERE author_id = ?");
        $stmt->execute([$authorId]);

        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new Book(
                (int)$row['id'],
                $row['title'],
                (int)$row['year'],
                (int)$row['author_id']
            );
        }
        return $result;
    }

    /**
     * Get a book by ID.
     *
     * @param int $bookId
     * @return Book|null
     */
    public function getBookById(int $bookId): ?Book
    {
        $stmt = DatabaseConnection::connect()->prepare("SELECT * FROM books WHERE id = ?");
        $stmt->execute([$bookId]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new Book(
            (int)$row['id'],
            $row['title'],
            (int)$row['year'],
            (int)$row['author_id']
        );
    }

    /**
     * Create a new book by the author.
     *
     * @param Book $book
     * @return bool
     * @throws Exception
     */
    public function createBook(Book $book): bool
    {
        $stmt = DatabaseConnection::connect()->prepare("INSERT INTO books (title, year, author_id) VALUES (?, ?, ?)");
        $success = $stmt->execute([
            $book->getTitle(),
            $book->getYear(),
            $book->getAuthorId()
        ]);

        if (!$success) {
            throw new Exception("Failed to create book");
        }

        return true;
    }

    /**
     * Edit an existing book by the author.
     *
     * @param Book $book
     * @return bool
     * @throws Exception
     */
    public function editBook(Book $book): bool
    {
        $stmt = DatabaseConnection::connect()->prepare("UPDATE books SET title = ?, year = ? WHERE id = ?");
        $success = $stmt->execute([
            $book->getTitle(),
            $book->getYear(),
            $book->getId()
        ]);

        if (!$success || $stmt->rowCount() === 0) {
            throw new Exception("Failed to update book or book not found.");
        }

        return true;
    }

    /**
     * Delete the author's book.
     *
     * @param int $bookId
     * @return bool
     * @throws Exception
     */
    public function deleteBook(int $bookId): bool
    {
        $stmt = DatabaseConnection::connect()->prepare("DELETE FROM books WHERE id = ?");
        $success = $stmt->execute([$bookId]);

        if (!$success || $stmt->rowCount() === 0) {
            throw new Exception("Failed to delete book or book not found.");
        }

        return true;
    }
}
