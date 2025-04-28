<?php

namespace Repository;

use Exception;

class AuthorRepository
{
    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION['authors'])) {
            $_SESSION['authors'] = [];
        }

        if (!isset($_SESSION['last_author_id'])) {
            $_SESSION['last_author_id'] = 0;
        }
    }

    /**
     * Get all authors from session.
     *
     * @return array List of authors
     */
    public function getAllAuthors(): array
    {
        return $_SESSION['authors'];
    }

    /**
     * Create a new author.
     *
     * @param $first_name
     * @param $last_name
     * @return void
     */
    public function createAuthor($first_name, $last_name): void
    {
        $_SESSION['last_author_id']++;
        $newID = $_SESSION['last_author_id'];

        $_SESSION['authors'][] = [
            'id' => $newID,
            'first_name' => $first_name,
            'last_name' => $last_name,
        ];
    }

    /**
     * Edit an existing author.
     *
     * @param int $authorId Author ID
     * @param string $first_name
     * @param string $last_name
     * @return void
     * @throws Exception
     */
    public function editAuthor(int $authorId, string $first_name, string $last_name): void
    {
        $authors = $this->getAllAuthors();
        foreach ($authors as $key => $author) {
            if($author['id'] === $authorId) {
                $authors[$key]['first_name'] = $first_name;
                $authors[$key]['last_name'] = $last_name;
                $_SESSION['authors'] = $authors;
                return;
            }
        }
        throw new Exception('Author not found.');
    }

    /**
     * Get an author by ID.
     *
     * @param int $id Author ID
     * @return array|null Author data or null if not found
     */
    public function getAuthorById(int $id): ?array
    {
        $authors = $this->getAllAuthors();
        foreach ($authors as $author) {
            if ($author['id'] === $id) {
                return $author;
            }
        }
        return null;
    }

    /**
     * Delete an author by ID.
     *
     * @param int $id Author ID
     * @return void
     * @throws Exception
     */
    public function deleteAuthor(int $id): void
    {
        $authors = $this->getAllAuthors();
        foreach ($authors as $key => $author) {
            if ($author['id'] === $id) {
                unset($authors[$key]);
                $_SESSION['authors'] = array_values($authors);

                return;
            }
        }
        throw new Exception('Author not found.');
    }
}
