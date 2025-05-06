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
     * @param int $author_id
     * @return array
     */
    public function get_by_author_id(int $author_id): array
    {
        $author_books = [];
        foreach ($_SESSION["books"] as $book) {
            if (isset($book['author_id']) && $book['author_id'] === $author_id) {
                $author_books[] = $book;
            }
        }
        return $author_books;
    }

    /**
     * @param int $book_id
     * @return array|null
     */
    public function get_book_by_id(int $book_id): ?array
    {
        return $_SESSION['books'][$book_id] ?? null;
    }

    /**
     * @param string $title
     * @param int $year
     * @param int $author_id
     * @return void
     */
    public function create_book(string $title, int $year, int $author_id): void
    {
        $id = $this->generate_next_id();
        $_SESSION['books'][$id] = [
            'id' => $id,
            'title' => $title,
            'year' => $year,
            'author_id' => $author_id,
        ];
    }

    /**
     * @param int $book_id
     * @param string $title
     * @param int $year
     * @return void
     * @throws Exception
     */
    public function edit_book(int $book_id, string $title, int $year): void
    {
        if(!isset($_SESSION['books'][$book_id])) {
            throw new Exception('Book not found');
        }

        $_SESSION['books'][$book_id]['title'] = $title;
        $_SESSION['books'][$book_id]['year'] = $year;
    }

    /**
     * @param int $book_id
     * @return void
     * @throws Exception
     */
    public function delete_book(int $book_id): void
    {
        if(!isset($_SESSION['books'][$book_id])) {
            throw new Exception('Book not found');
        }

        unset($_SESSION['books'][$book_id]);
    }

    public function delete_by_author_id(int $author_id): void
    {
        $books_to_keep = [];
        foreach ($_SESSION['books'] as $book_id => $book) {
            if ($book['author_id'] !== $author_id) {
                $books_to_keep[$book_id] = $book;
            }
        }
        $_SESSION['books'] = $books_to_keep;
    }

    private function generate_next_id()
    {
        if(empty($_SESSION['books'])) {
            return 1;
        }

        return max(array_keys($_SESSION['books'])) + 1;
    }
}