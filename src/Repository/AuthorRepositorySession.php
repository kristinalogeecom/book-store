<?php

namespace BookStore\Repository;

use BookStore\Infrastructure\Session;
use BookStore\Models\Author;
use Exception;

class AuthorRepositorySession implements AuthorRepositoryInterface
{
    private Session $session;

    public function __construct()
    {
       $this->session = Session::getInstance();

       if(!$this->session->get('authors')) {
           $this->session->set('authors', []);
       }
    }

    /**
     * Get all authors from session.
     *
     * @return array
     */
    public function getAllAuthors(): array
    {
        return array_values($this->session->get('authors'));
    }

    /**
     * Create a new author.
     *
     * @param Author $author
     * @return void
     */
    public function createAuthor(Author $author): void
    {
        $authors = $this->session->get('authors');
        $id = $this->generateNextId($authors);

        $authors[$id] = [
            'id' => $id,
            'first_name' => $firstName,
            'last_name' => $lastName,
        ];
        $this->session->set('authors', $authors);
    }

    /**
     * Edit an existing author.
     *
     * @param int $authorId
     * @param string $firstName
     * @param string $lastName
     * @return void
     * @throws Exception
     */
    public function editAuthor(int $authorId, string $firstName, string $lastName): void
    {
        $authors = $this->session->get('authors');

        if(!isset($authors[$authorId])) {
            throw new Exception('Author not found');
        }

        $authors[$authorId]['first_name'] = $firstName;
        $authors[$authorId]['last_name'] = $lastName;

        $this->session->set('authors', $authors);
    }

    /**
     * Delete an author by ID.
     *
     * @param int $authorId
     * @return void
     * @throws Exception
     */
    public function deleteAuthor(int $authorId): void
    {
        $authors = $this->session->get('authors');

        if(!isset($authors[$authorId])) {
            throw new Exception('Author not found');
        }

        unset($authors[$authorId]);
        $this->session->set('authors', $authors);
    }

    /**
     * Get an author by ID.
     *
     * @param int $authorId
     * @return array|null
     */
    public function getAuthorById(int $authorId): ?array
    {
        $authors = $this->session->get('authors');
        return $authors[$authorId] ?? null;
    }

    /**
     * Generate next ID.
     *
     * @return int
     */
    private function generateNextId(): int
    {
        if(empty($authors)) {
            return 1;
        }

        return max(array_keys($authors)) + 1;
    }
}
