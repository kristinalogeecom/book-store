<?php

namespace BookStore\Application\BusinessLogic\Service;

use BookStore\Application\BusinessLogic\Models\Book;
use BookStore\Application\BusinessLogic\RepositoryInterfaces\BookRepositoryInterface;
use BookStore\Application\BusinessLogic\ServiceInterfaces\BookServiceInterface;
use Exception;
use function DI\string;

class BookService implements BookServiceInterface
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
    public function getAllBooksForAuthor(int $authorId): array
    {
        return $this->bookRepository->getAllBooksForAuthor($authorId);
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
        $this->validateBookData($book);

        $this->bookRepository->createBook($book);
    }

    /**
     * Edit an existing book by the author.
     *
     * @param Book $book
     * @return void
     * @throws Exception
     */
    public function editBook(Book $book): void
    {
        $this->validateBookData($book);

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
     * @param Book $book
     * @return void
     * @throws Exception
     */
    public function validateBookData(Book $book): void
    {
        $title = $book->getTitle();
        $year = $book->getYear();
        $errors = [];

        if (empty($title)) {
            $errors['title'] = 'Title is a required field';
        }
        if (!empty($title) && strlen($title) > 250) {
            $errors['title'] = 'Title is too long';
        }

        if (!is_numeric($year)) {
            $errors['year'] = 'The year must be a number.';
        }
        if (is_numeric($year) && ($year < -5000 || $year > 999999 || $year == 0)) {
            $errors['year'] = 'The year must be between -5000 and 999999, and cannot be 0.';
        }

        if (!empty($errors)) {
            throw new \Exception(json_encode($errors));
        }
    }
}