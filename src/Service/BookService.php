<?php

namespace BookStore\Service;

use BookStore\Repository\BookRepositoryInterface;

class BookService
{
    private BookRepositoryInterface $book_repository;

    public function __construct(BookRepositoryInterface $book_repository)
    {
        $this->book_repository = $book_repository;
    }

    public function get_by_author_id(int $author_id): array
    {
        return $this->book_repository->get_by_author_id($author_id);
    }

    public function create_book(string $title, int $year, int $author_id): void
    {
        $this->book_repository->create_book($title, $year, $author_id);
    }

    public function edit_book(int $book_id, string $title, int $year): void
    {
        $this->book_repository->edit_book($book_id, $title, $year);
    }

    public function delete_book(int $book_id): void
    {
        $this->book_repository->delete_book($book_id);
    }

    public function get_book_by_id(int $book_id): ?array
    {
        return $this->book_repository->get_book_by_id($book_id);
    }
}