<?php

namespace BookStore\Repository;

interface AuthorRepositoryInterface
{
    public function get_all_authors(): array;

    public function create_author(string $first_name, string $last_name): void;

    public function edit_author(int $author_id, string $first_name, string $last_name): void;

    public function delete_author(int $author_id): void;

    public function get_author_by_id(int $author_id): ?array;
}