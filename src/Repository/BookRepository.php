<?php

namespace BookStore\Repository;

use BookStore\Models\Book;
use PDO;
use Exception;

class BookRepository implements BookRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /** @return Book[] */
    public function getByAuthorId(int $authorId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM books WHERE author_id = ?");
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
        $stmt = $this->pdo->prepare("SELECT * FROM books WHERE id = ?");
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
        $stmt = $this->pdo->prepare("INSERT INTO books (title, year, author_id) VALUES (?, ?, ?)");
        $stmt->execute([
            $book->getTitle(),
            $book->getYear(),
            $book->getAuthorId()
        ]);
    }

    public function editBook(Book $book): void
    {
        $stmt = $this->pdo->prepare("UPDATE books SET title = ?, year = ? WHERE id = ?");
        $stmt->execute([
            $book->getTitle(),
            $book->getYear(),
            $book->getId()
        ]);
    }

    public function deleteBook(int $bookId): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM books WHERE id = ?");
        $stmt->execute([$bookId]);
    }

    public function deleteByAuthorId(int $authorId): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM books WHERE author_id = ?");
        $stmt->execute([$authorId]);
    }

    public function countByAuthorId(int $authorId): int
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM books WHERE author_id = ?");
        $stmt->execute([$authorId]);

        return (int)$stmt->fetchColumn();
    }
}
