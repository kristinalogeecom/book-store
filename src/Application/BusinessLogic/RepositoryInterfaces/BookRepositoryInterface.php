<?php

namespace BookStore\Application\BusinessLogic\RepositoryInterfaces;

use BookStore\Application\BusinessLogic\Models\Book;

interface BookRepositoryInterface
{
    public function getAllBooksForAuthor(int $authorId): array;

    public function getBookById(int $bookId): ?Book;

    public function createBook(Book $book): bool;

    public function editBook(Book $book): bool;

    public function deleteBook(int $bookId): bool;
}