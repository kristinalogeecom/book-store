<?php

namespace BookStore\Repository;

interface AuthorRepositoryInterface
{
    public function getAllAuthors(): array;

    public function createAuthor(string $firstName, string $lastName): void;

    public function editAuthor(int $authorId, string $firstName, string $lastName): void;

    public function deleteAuthor(int $authorId): void;

    public function getAuthorById(int $authorId): ?array;
}