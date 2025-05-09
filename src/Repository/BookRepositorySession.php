<?php

namespace BookStore\Repository;

use BookStore\Infrastructure\Session;
use BookStore\Models\Book;
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
        $booksData = $this->session->get('books');
        $authorBooks = [];

        foreach ($booksData as $book) {
            if ($book instanceof Book && $book->getAuthorId() === $authorId) {
                $authorBooks[] = $book;
            }
        }

        return $authorBooks;
    }

    /**
     * @param Book $book
     * @return void
     */
    public function createBook(Book $book): void
    {
        $books = $this->session->get('books');
        $id = $this->generateNextId($books);

        $book->setId($id);
        $books[$id] = $book;

        $this->session->set('books', $books);
    }

    /**
     * @param Book $book
     * @return void
     * @throws Exception
     */
    public function editBook(Book $book): void
    {
        $books = $this->session->get('books');
        $bookId = $book->getId();

        if(!isset($books[$bookId])) {
            throw new Exception('Book not found');
        }

        $books[$bookId] = $book;

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
    public function getBookById(int $bookId): ?Book
    {
        $books = $this->session->get('books');

        return $books[$bookId] ?? null;
    }

    public function deleteByAuthorId(int $authorId): void
    {
        $books = $this->session->get('books');

        foreach ($books as $id => $book) {
            if ($book instanceof Book && $book->getAuthorId() === $authorId) {
                unset($books[$id]);
            }
        }

        $this->session->set('books', $books);
    }

    private function generateNextId(array $books): int
    {
        if (empty($books)) {
            return 1;
        }

        $maxId = 0;
        foreach ($books as $id => $book) {
            if ($book instanceof Book && $id > $maxId) {
                $maxId = $id;
            }
        }

        return $maxId + 1;
    }

    public function countByAuthorId(int $authorId): int
    {
        $books = $this->session->get('books');
        $count = 0;
        foreach ($books as $book) {
            if ($book instanceof Book && $book->getAuthorId() === $authorId) {
                $count++;
            }
        }
        return $count;
    }
}