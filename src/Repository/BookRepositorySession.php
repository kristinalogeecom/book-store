<?php

namespace BookStore\Repository;

use BookStore\Infrastructure\Session;
use Exception;

class BookRepositorySession implements BookRepositoryInterface
{
    private Session $session;


    public function __construct()
    {
        $this->session = Session::getInstance();

        if($this->session->get('books') === null) {
            $this->session->set('books', []);
        }

    }

    /**
     * @param int $authorId
     * @return array
     */
    public function getByAuthorId(int $authorId): array
    {
        $books = $this->session->get('books');
        $authorBooks = [];

        foreach ($books as $book) {
            if (isset($book['author_id']) && $book['author_id'] === $authorId) {
                $authorBooks[] = $book;
            }
        }
        return $authorBooks;
    }

    /**
     * @param string $title
     * @param int $year
     * @param int $authorId
     * @return void
     */
    public function createBook(string $title, int $year, int $authorId): void
    {
        $books = $this->session->get('books');
        $id = $this->generateNextId($books);

        $books[$id] = [
            'id' => $id,
            'title' => $title,
            'year' => $year,
            'author_id' => $authorId,
        ];
        $this->session->set('books', $books);
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
        $books = $this->session->get('books');

        if(!isset($books[$bookId])) {
            throw new Exception('Book not found');
        }

        $books[$bookId]['title'] = $title;
        $books[$bookId]['year'] = $year;

        $this->session->set('books', $books);
    }

    /**
     * @param int $bookId
     * @return void
     * @throws Exception
     */
    public function deleteBook(int $bookId): void
    {
        $books = $this->session->get('books');

        if(!isset($books[$bookId])) {
            throw new Exception('Book not found');
        }

        unset($books[$bookId]);

        $this->session->set('books', $books);
    }


    /**
     * @param int $bookId
     * @return array|null
     */
    public function getBookById(int $bookId): ?array
    {
        $books = $this->session->get('books');

        return $books[$bookId] ?? null;
    }

    public function deleteByAuthorId(int $authorId): void
    {
        $books = $this->session->get('books');

        $booksToKeep = [];
        foreach ($books as $bookId => $book) {
            if ($book['author_id'] !== $authorId) {
                $booksToKeep[$bookId] = $book;
            }
        }
        $this->session->set('books', $booksToKeep);
    }

    private function generateNextId(array $books): int
    {
        if(empty($books)) {
            return 1;
        }

        return max(array_keys($books)) + 1;
    }

    public function countByAuthorId(int $authorId): int
    {
        $books = $this->session->get('books');
        $count = 0;
        foreach ($books as $book) {
            if ($book['author_id'] === $authorId) {
                $count += 1;
            }
        }
        return $count;
    }
}