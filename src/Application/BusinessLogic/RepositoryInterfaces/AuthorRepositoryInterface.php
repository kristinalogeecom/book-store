<?php

namespace BookStore\Application\BusinessLogic\RepositoryInterfaces;

use BookStore\Application\BusinessLogic\Models\Author;

interface AuthorRepositoryInterface
{
    public function getAllAuthors(): array;

    public function createAuthor(Author $author): void;

    public function editAuthor(Author $author): void;

    public function deleteAuthor(int $authorId): void;

    public function getAuthorById(int $authorId): ?Author;
}