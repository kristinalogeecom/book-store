<?php

namespace BookStore\Repository;

interface BookRepositoryInterface
{
    public function get_by_author_id(int $author_id): array;
    public function get_book_by_id(int $book_id): ?array;
    public function create_book(string $title, int $year, int $author_id): void;
    public function edit_book(int $book_id, string $title, int $year): void;
    public function delete_book(int $book_id): void;
    public function delete_by_author_id(int $author_id): void;

}