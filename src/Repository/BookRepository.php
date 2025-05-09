<?php

namespace BookStore\Repository;

use BookStore\Database\DatabaseConnection;
use BookStore\Models\Book;
use PDO;
use Exception;

class BookRepository implements BookRepositoryInterface
{
    /** @return Book[] */
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

    public function createBook(Book $book): void
    {
        $stmt = DatabaseConnection::connect()->prepare("INSERT INTO books (title, year, author_id) VALUES (?, ?, ?)");
        $stmt->execute([
            $book->getTitle(),
            $book->getYear(),
            $book->getAuthorId()
        ]);
    }

    public function editBook(Book $book): void
    {
        $stmt = DatabaseConnection::connect()->prepare("UPDATE books SET title = ?, year = ? WHERE id = ?");
        $stmt->execute([
            $book->getTitle(),
            $book->getYear(),
            $book->getId()
        ]);
    }

    public function deleteBook(int $bookId): void
    {
        $stmt = DatabaseConnection::connect()->prepare("DELETE FROM books WHERE id = ?");
        $stmt->execute([$bookId]);
    }

    public function deleteByAuthorId(int $authorId): void
    {
        $stmt = DatabaseConnection::connect()->prepare("DELETE FROM books WHERE author_id = ?");
        $stmt->execute([$authorId]);
    }


}
