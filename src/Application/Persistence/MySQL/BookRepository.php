<?php

namespace BookStore\Application\Persistence\MySQL;

use BookStore\Application\BusinessLogic\RepositoryInterfaces\BookRepositoryInterface;
use BookStore\Infrastructure\Database\DatabaseConnection;
use BookStore\Application\BusinessLogic\Models\Book;
use PDO;

class BookRepository implements BookRepositoryInterface
{

    /**
     * Returns all books by the author.
     *
     * @param int $authorId
     * @return array
     */
    public function getByAuthorId(int $authorId): array
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
     * @return void
     */
    public function createBook(Book $book): void
    {
        $stmt = DatabaseConnection::connect()->prepare("INSERT INTO books (title, year, author_id) VALUES (?, ?, ?)");
        $stmt->execute([
            $book->getTitle(),
            $book->getYear(),
            $book->getAuthorId()
        ]);
    }

    /**
     * Edit an existing book by the author.
     *
     * @param Book $book
     * @return void
     */
    public function editBook(Book $book): void
    {
        $stmt = DatabaseConnection::connect()->prepare("UPDATE books SET title = ?, year = ? WHERE id = ?");
        $stmt->execute([
            $book->getTitle(),
            $book->getYear(),
            $book->getId()
        ]);
    }

    /**
     * Delete the author's book.
     *
     * @param int $bookId
     * @return void
     */
    public function deleteBook(int $bookId): void
    {
        $stmt = DatabaseConnection::connect()->prepare("DELETE FROM books WHERE id = ?");
        $stmt->execute([$bookId]);
    }

}
