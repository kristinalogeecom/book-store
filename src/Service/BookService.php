<?php

namespace BookStore\Service;

use BookStore\Repository\BookRepositoryInterface;
use BookStore\Models\Book;

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

    public function createBook(Book $book): void
    {
        $this->bookRepository->createBook($book);
    }

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
}