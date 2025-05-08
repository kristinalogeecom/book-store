<?php

namespace BookStore\Repository;

use BookStore\Models\Book;

interface BookRepositoryInterface
{
    public function getByAuthorId(int $authorId): array;
    public function getBookById(int $bookId): ?Book;
    public function createBook(Book $book): void;
    public function editBook(Book $book): void;
    public function deleteBook(int $bookId): void;
    public function deleteByAuthorId(int $authorId): void;

    public function countByAuthorId(int $authorId): int;

}