<?php

namespace BookStore\Repository;

use Exception;

class BookRepositorySession implements BookRepositoryInterface
{

    public function __construct()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        if (!isset($_SESSION["books"])){
            $_SESSION["books"] = [];
        }
    }

    /**
     * @param int $authorId
     * @return array
     */
    public function getByAuthorId(int $authorId): array
    {
        $authorBooks = [];
        foreach ($_SESSION["books"] as $book) {
            if (isset($book['author_id']) && $book['author_id'] === $authorId) {
                $authorBooks[] = $book;
            }
        }
        return $authorBooks;
    }

    /**
     * @param int $bookId
     * @return array|null
     */
    public function getBookById(int $bookId): ?array
    {
        return $_SESSION['books'][$bookId] ?? null;
    }

    /**
     * @param string $title
     * @param int $year
     * @param int $authorId
     * @return void
     */
    public function createBook(string $title, int $year, int $authorId): void
    {
        $id = $this->generateNextId();
        $_SESSION['books'][$id] = [
            'id' => $id,
            'title' => $title,
            'year' => $year,
            'author_id' => $authorId,
        ];
    }

    /**
     * @param int $bookId
     * @param string $title
     * @param int $year
     * @return void
     * @throws Exception
     */
    public function editBook(int $bookId, string $title, int $year): void
    {
        if(!isset($_SESSION['books'][$bookId])) {
            throw new Exception('Book not found');
        }

        $_SESSION['books'][$bookId]['title'] = $title;
        $_SESSION['books'][$bookId]['year'] = $year;
    }

    /**
     * @param int $bookId
     * @return void
     * @throws Exception
     */
    public function deleteBook(int $bookId): void
    {
        if(!isset($_SESSION['books'][$bookId])) {
            throw new Exception('Book not found');
        }

        unset($_SESSION['books'][$bookId]);
    }

    public function deleteByAuthorId(int $authorId): void
    {
        $booksToKeep = [];
        foreach ($_SESSION['books'] as $bookId => $book) {
            if ($book['author_id'] !== $authorId) {
                $booksToKeep[$bookId] = $book;
            }
        }
        $_SESSION['books'] = $booksToKeep;
    }

    private function generateNextId()
    {
        if(empty($_SESSION['books'])) {
            return 1;
        }

        return max(array_keys($_SESSION['books'])) + 1;
    }
}