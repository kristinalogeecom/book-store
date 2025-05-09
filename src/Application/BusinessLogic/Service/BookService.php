<?php

namespace BookStore\Application\BusinessLogic\Service;

use BookStore\Application\BusinessLogic\Models\Book;
use BookStore\Application\BusinessLogic\RepositoryInterfaces\BookRepositoryInterface;
use Exception;

class BookService
{
    /**
     * @var BookRepositoryInterface
     */
    private BookRepositoryInterface $bookRepository;

    /**
     * @param BookRepositoryInterface $bookRepository
     */
    public function __construct(BookRepositoryInterface $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    /**
     * Returns all books by the author.
     *
     * @param int $authorId
     * @return array
     */
    public function getByAuthorId(int $authorId): array
    {
        return $this->bookRepository->getByAuthorId($authorId);
    }

    /**
     * Create a new book by the author.
     *
     * @param Book $book
     * @return void
     * @throws Exception
     */
    public function createBook(Book $book): void
    {
        $errors = $this->validateBookData($book->getTitle(), $book->getYear());

        if (!empty($errors)) {
            throw new Exception(implode("\n", $errors));
        }

        $this->bookRepository->createBook($book);
    }

    /**
     * Edit an existing book by the author.
     *
     * @param Book $book
     * @return void
     */
    public function editBook(Book $book): void
    {
        $this->bookRepository->editBook($book);
    }

    /**
     * Delete the author's book.
     *
     * @param int $bookId
     * @return void
     */
    public function deleteBook(int $bookId): void
    {
        $this->bookRepository->deleteBook($bookId);
    }

    /**
     * Get a book by ID.
     *
     * @param int $bookId
     * @return Book|null
     */
    public function getBookById(int $bookId): ?Book
    {
        return $this->bookRepository->getBookById($bookId);
    }

    /**
     * Validate book data.
     *
     * @param string $title
     * @param int $year
     * @return array
     */
    public function validateBookData(string $title, int $year): array
    {
        $errors = [];

        if (empty($title)) {
            $errors['title'] = 'Title is a required field';
        } elseif (strlen($title) > 250) {
            $errors['title'] = 'Title is too long';
        }

        if (!is_numeric($year)) {
            $errors['year'] = 'The year must be a number.';
        } elseif ($year < -5000 || $year > 999999 || $year == 0) {
            $errors['year'] = 'The year must be between -5000 and 999999, and cannot be 0.';
        }

        return $errors;
    }
}