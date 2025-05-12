<?php

namespace BookStore\Application\BusinessLogic\ServiceInterfaces;

use BookStore\Application\BusinessLogic\Models\Author;

interface AuthorServiceInterface
{
    public function getAuthors(): array;

    public function createAuthor(Author $author): void;

    public function editAuthor(Author $author): void;

    public function getAuthorById(int $id): ?Author;

    public function deleteAuthor(int $id): void;
}