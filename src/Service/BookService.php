<?php

namespace BookStore\Service;

use BookStore\Repository\BookRepositoryInterface;

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

    public function createBook(string $title, int $year, int $authorId): void
    {
        $this->bookRepository->createBook($title, $year, $authorId);
    }

    public function editBook(int $bookId, string $title, int $year): void
    {
        $this->bookRepository->editBook($bookId, $title, $year);
    }

    public function deleteBook(int $bookId): void
    {
        $this->bookRepository->deleteBook($bookId);
    }

    public function getBookById(int $bookId): ?array
    {
        return $this->bookRepository->getBookById($bookId);
    }
}