<?php

namespace BookStore\Application\BusinessLogic\RepositoryInterfaces;

use BookStore\Application\BusinessLogic\Models\Author;

interface AuthorRepositoryInterface
{
    public function getAllAuthors(): array;

    public function createAuthor(Author $author): bool;

    public function editAuthor(Author $author): bool;

    public function deleteAuthor(int $authorId): bool;

    public function getAuthorById(int $authorId): ?Author;
}