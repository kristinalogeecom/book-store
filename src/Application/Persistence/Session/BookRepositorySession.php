<?php

namespace BookStore\Application\Persistence\Session;

use BookStore\Application\BusinessLogic\RepositoryInterfaces\BookRepositoryInterface;
use BookStore\Infrastructure\Session\Session;
use BookStore\Application\BusinessLogic\Models\Book;
use Exception;

class BookRepositorySession implements BookRepositoryInterface
{
    public function __construct()
    {
        if (!Session::getInstance()->get('books')) {
            Session::getInstance()->set('books', []);
        }
    }

    /**
     * Returns all books by the author from session.
     *
     * @param int $authorId
     * @return array
     */
    public function getAllBooksForAuthor(int $authorId): array
    {
        $booksData = Session::getInstance()->get('books');
        $authorBooks = [];

        foreach ($booksData as $book) {
            if ($book instanceof Book && $book->getAuthorId() === $authorId) {
                $authorBooks[] = $book;
            }
        }
        return $authorBooks;
    }

    /**
     * Create a new book by the author.
     *
     * @param Book $book
     * @return bool
     */
    public function createBook(Book $book): bool
    {
        $books = Session::getInstance()->get('books');
        $id = $this->generateNextId($books);

        $book->setId($id);
        $books[$id] = $book;

        Session::getInstance()->set('books', $books);

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
        $books = Session::getInstance()->get('books');
        $bookId = $book->getId();

        if (!isset($books[$bookId])) {
            throw new Exception('Book not found');
        }

        $books[$bookId] = $book;
        Session::getInstance()->set('books', $books);

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
        $books = Session::getInstance()->get('books');

        if (!isset($books[$bookId])) {
            throw new Exception('Book not found');
        }

        unset($books[$bookId]);
        Session::getInstance()->set('books', $books);

        return true;
    }


    /**
     * Get a book by ID.
     *
     * @param int $bookId
     * @return array|null
     */
    public function getBookById(int $bookId): ?Book
    {
        $books = Session::getInstance()->get('books');

        return $books[$bookId] ?? null;
    }

    /**
     * Generate next book ID
     *
     * @param array $books
     * @return int
     */
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
}