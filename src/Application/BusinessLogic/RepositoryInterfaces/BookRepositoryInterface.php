<?php

namespace BookStore\Application\BusinessLogic\RepositoryInterfaces;

use BookStore\Application\BusinessLogic\Models\Book;

/**
 * Defines methods for managing Book entities
 */
interface BookRepositoryInterface
{
    /**
     * @param int $authorId
     * @return array
     */
    public function getAllBooksForAuthor(int $authorId): array;

    /**
     * @param int $bookId
     * @return Book|null
     */
    public function getBookById(int $bookId): ?Book;

    /**
     * @param Book $book
     * @return bool
     */
    public function createBook(Book $book): bool;

    /**
     * @param Book $book
     * @return bool
     */
    public function editBook(Book $book): bool;

    /**
     * @param int $bookId
     * @return bool
     */
    public function deleteBook(int $bookId): bool;
}