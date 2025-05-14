<?php

namespace BookStore\Application\BusinessLogic\ServiceInterfaces;

use BookStore\Application\BusinessLogic\Models\Book;

/**
 * Defines the business logic operations related to managing Book entities
 */
interface BookServiceInterface
{
    /**
     * @param int $authorId
     * @return array
     */
    public function getAllBooksForAuthor(int $authorId): array;

    /**
     * @param Book $book
     * @return void
     */
    public function createBook(Book $book): void;

    /**
     * @param Book $book
     * @return void
     */
    public function editBook(Book $book): void;

    /**
     * @param int $bookId
     * @return void
     */
    public function deleteBook(int $bookId): void;

    /**
     * @param int $bookId
     * @return Book|null
     */
    public function getBookById(int $bookId): ?Book;
}