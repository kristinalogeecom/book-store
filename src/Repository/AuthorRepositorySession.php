<?php

namespace BookStore\Repository;

use Exception;

class AuthorRepositorySession implements AuthorRepositoryInterface
{
    public function __construct()
    {
        if(!isset($_SESSION['authors'])) {
            $_SESSION['authors'] = [];
        }
    }

    /**
     * Get all authors from session.
     *
     * @return array
     */
    public function get_all_authors(): array
    {
        return array_values($_SESSION['authors']);
    }

    /**
     * Create a new author.
     *
     * @param string $first_name
     * @param string $last_name
     * @return void
     */
    public function create_author(string $first_name, string $last_name): void
    {
        $id = $this->generate_next_id();
        $_SESSION['authors'][$id] = [
            'id' => $id,
            'first_name' => $first_name,
            'last_name' => $last_name,
        ];
    }

    /**
     * Edit an existing author.
     *
     * @param int $author_id
     * @param string $first_name
     * @param string $last_name
     * @return void
     * @throws Exception
     */
    public function edit_author(int $author_id, string $first_name, string $last_name): void
    {
        if(!isset($_SESSION['authors'][$author_id])) {
            throw new Exception('Author not found');
        }

        $_SESSION['authors'][$author_id]['first_name'] = $first_name;
        $_SESSION['authors'][$author_id]['last_name'] = $last_name;
    }

    /**
     * Delete an author by ID.
     *
     * @param int $author_id
     * @return void
     * @throws Exception
     */
    public function delete_author(int $author_id): void
    {
        if(!isset($_SESSION['authors'][$author_id])) {
            throw new Exception('Author not found');
        }

        unset($_SESSION['authors'][$author_id]);
    }

    /**
     * Get an author by ID.
     *
     * @param int $author_id
     * @return array|null
     */
    public function get_author_by_id(int $author_id): ?array
    {
        return $_SESSION['authors'][$author_id] ?? null;
    }

    /**
     * Generate next ID.
     *
     * @return int
     */
    private function generate_next_id(): int
    {
        if(empty($_SESSION['authors'])) {
            return 1;
        }

        return max(array_keys($_SESSION['authors'])) + 1;
    }
}
