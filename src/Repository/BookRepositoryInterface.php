<?php

namespace BookStore\Repository;

interface BookRepositoryInterface
{
    public function getByAuthorId(int $authorId): array;
    public function getBookById(int $bookId): ?array;
    public function createBook(string $title, int $year, int $authorId): void;
    public function editBook(int $bookId, string $title, int $year): void;
    public function deleteBook(int $bookId): void;
    public function deleteByAuthorId(int $authorId): void;

}