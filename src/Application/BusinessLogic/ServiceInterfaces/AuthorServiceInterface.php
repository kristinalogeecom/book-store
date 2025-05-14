<?php

namespace BookStore\Application\BusinessLogic\ServiceInterfaces;

use BookStore\Application\BusinessLogic\Models\Author;
/**
 * Defines the business logic operations related to managing Author entities
 */
interface AuthorServiceInterface
{
    /**
     * @return array
     */
    public function getAuthors(): array;

    /**
     * @param Author $author
     * @return void
     */
    public function createAuthor(Author $author): void;

    /**
     * @param Author $author
     * @return void
     */
    public function editAuthor(Author $author): void;

    /**
     * @param int $id
     * @return Author|null
     */
    public function getAuthorById(int $id): ?Author;

    /**
     * @param int $id
     * @return void
     */
    public function deleteAuthor(int $id): void;
}