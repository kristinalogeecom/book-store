<?php

namespace BookStore\Application\BusinessLogic\RepositoryInterfaces;

use BookStore\Application\BusinessLogic\Models\Book;

interface BookRepositoryInterface
{
    public function getByAuthorId(int $authorId): array;

    public function getBookById(int $bookId): ?Book;

    public function createBook(Book $book): void;

    public function editBook(Book $book): void;

    public function deleteBook(int $bookId): void;

}