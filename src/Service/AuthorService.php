<?php

namespace Service;

use Exception;
use Repository\AuthorRepository;
session_start();

class AuthorService
{
    private AuthorRepository $repository;

    public function __construct(AuthorRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAuthorsWithBookCount(): array
    {
        $authors = $this->repository->getAllAuthors();
        $books = $this->repository->getAllBooks();

        $bookCounts = [];
        foreach ($books as $book) {
            $authorId = $book['author']['id'];
            $bookCounts[$authorId] = ($bookCounts[$authorId] ?? 0) + 1;
        }

        foreach ($authors as &$author) {
            $authorId = $author['id'];
            $author['books'] = $bookCounts[$authorId] ?? 0;
        }

        return $authors;
    }

    public function getAuthors(): array
    {
        return $this -> repository -> getAllAuthors();
    }

    /**
     * @throws Exception
     */
    public function createAuthor(mixed $first_name, mixed $last_name): void
    {
        if(strlen($first_name) > 100) {
            throw new Exception('First name cannot exceed 100 characters.');
        }

        if(strlen($last_name) > 100) {
            throw new Exception('Last name cannot exceed 100 characters.');
        }

        $this -> repository -> createAuthor($first_name, $last_name);
    }

    /**
     * @throws Exception
     */
    public function editAuthor(mixed $authorId, string $first_name, string $last_name): void
    {
        if(strlen($first_name) > 100) {
            throw new Exception('First name cannot exceed 100 characters.');
        }

        if(strlen($last_name) > 100) {
            throw new Exception('Last name cannot exceed 100 characters.');
        }

        $this -> repository -> editAuthor($authorId, $first_name, $last_name);
    }

    /**
     * @throws Exception
     */
    public function getAuthorById(mixed $id)
    {
        return $this->repository->getAuthorById($id);
    }

    public function deleteAuthor(mixed $id)
    {
        $this -> repository -> deleteAuthor($id);
    }
}
