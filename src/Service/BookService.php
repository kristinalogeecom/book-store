<?php

namespace BookStore\Service;

use BookStore\Repository\BookRepositoryInterface;
use BookStore\Models\Book;
use Exception;

class BookService
{
    private BookRepositoryInterface $bookRepository;

    public function __construct(BookRepositoryInterface $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function getByAuthorId(int $authorId): array
    {
        return $this->bookRepository->getByAuthorId($authorId);
    }

    /**
     * @throws Exception
     */
    public function createBook(Book $book): void
    {
        $errors = $this->validateBookData($book->getTitle(), $book->getYear());

        if(!empty($errors)) {
            throw new Exception(implode("\n", $errors));
        }

        $this->bookRepository->createBook($book);
    }

    /**
     * @throws Exception
     */
    public function editBook(Book $book): void
    {
        $this->bookRepository->editBook($book);
    }

    public function deleteBook(int $bookId): void
    {
        $this->bookRepository->deleteBook($bookId);
    }

    public function getBookById(int $bookId): ?Book
    {
        return $this->bookRepository->getBookById($bookId);
    }

    public function validateBookData(string $title, int $year): array
    {
        $errors = [];

        if(empty($title)) {
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