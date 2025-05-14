<?php

namespace BookStore\Application\BusinessLogic\RepositoryInterfaces;

use BookStore\Application\BusinessLogic\Models\Author;

/**
 * Defines methods for managing Author entities
 */
interface AuthorRepositoryInterface
{
    /**
     * @return array
     */
    public function getAllAuthors(): array;

    /**
     * @param Author $author
     * @return bool
     */
    public function createAuthor(Author $author): bool;

    /**
     * @param Author $author
     * @return bool
     */
    public function editAuthor(Author $author): bool;

    /**
     * @param int $authorId
     * @return bool
     */
    public function deleteAuthor(int $authorId): bool;

    /**
     * @param int $authorId
     * @return Author|null
     */
    public function getAuthorById(int $authorId): ?Author;
}