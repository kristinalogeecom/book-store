<?php

namespace BookStore\Application\BusinessLogic\ServiceInterfaces;

use BookStore\Application\BusinessLogic\Models\Book;

interface BookServiceInterface
{
    public function getByAuthorId(int $authorId): array;

    public function createBook(Book $book): void;

    public function editBook(Book $book): void;

    public function deleteBook(int $bookId): void;

    public function getBookById(int $bookId): ?Book;

}