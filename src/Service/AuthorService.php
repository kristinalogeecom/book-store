<?php

namespace BookStore\Service;

use Exception;
use BookStore\Repository\AuthorRepository;

class AuthorService
{
    /**
     * @var AuthorRepository
     */
    private AuthorRepository $repository;

    /**
     * @param AuthorRepository $repository
     */
    public function __construct(AuthorRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get all authors.
     *
     * @return array
     */
    public function get_authors(): array
    {
        return $this->repository->get_all_authors();
    }

    /**
     * Create a new author.
     *
     * @param string $first_name
     * @param string $last_name
     * @return void
     * @throws Exception
     */
    public function create_author(string $first_name, string $last_name): void
    {
        $errors = $this->validate_author_data($first_name, $last_name);

        if (!empty($errors)) {
            throw new Exception(json_encode($errors));
        }

        $this->repository->create_author($first_name, $last_name);
    }

    /**
     * Edit an existing author.
     *
     * @param int $author_id Author ID
     * @param string $first_name
     * @param string $last_name
     * @return void
     * @throws Exception
     */
    public function edit_author(int $author_id, string $first_name, string $last_name): void
    {
        $errors = $this->validate_author_data($first_name, $last_name);

        if (!empty($errors)) {
            throw new Exception(json_encode($errors));
        }

        $this->repository->edit_author($author_id, $first_name, $last_name);
    }

    /**
     * Get an author by ID.
     *
     * @param int $id Author ID
     * @return array|null Author data or null if not found
     */
    public function get_author_by_id(int $id): ?array
    {
        return $this->repository->get_author_by_id($id);
    }

    /**
     * Delete an author by ID.
     *
     * @param int $id Author ID
     * @return void
     * @throws Exception
     */
    public function delete_author(int $id): void
    {
        $this->repository->delete_author($id);
    }

    /**
     * Validate author data.
     *
     * @param string $first_name
     * @param string $last_name
     * @return array Validation errors
     */
    private function validate_author_data(string $first_name, string $last_name): array
    {
        $errors = [];

        if (empty($first_name)) {
            $errors['first_name'] = 'First name is required';
        }

        if (empty($last_name)) {
            $errors['last_name'] = 'Last name is required';
        }

        if (strlen($first_name) > 100) {
            $errors['first_name'] = 'First name cannot exceed 100 characters.';
        }

        if (strlen($last_name) > 100) {
            $errors['last_name'] = 'Last name cannot exceed 100 characters.';
        }

        return $errors;
    }
}
