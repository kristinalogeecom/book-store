<?php

namespace BookStore\Application\Persistence\Session;

use BookStore\Application\BusinessLogic\RepositoryInterfaces\AuthorRepositoryInterface;
use BookStore\Infrastructure\Session\Session;
use BookStore\Application\BusinessLogic\Models\Author;
use Exception;

class AuthorRepositorySession implements AuthorRepositoryInterface
{
    public function __construct()
    {
        if (!Session::getInstance()->get('authors')) {
            Session::getInstance()->set('authors', []);
        }
    }

    /**
     * Get all authors from session.
     *
     * @return array
     */
    public function getAllAuthors(): array
    {
        /** @var Author[] $authors */
        $authors = Session::getInstance()->get('authors') ?? [];
        $books = Session::getInstance()->get('books') ?? [];

        foreach ($authors as $author) {
            $authorBooks = array_filter($books, function ($book) use ($author) {
                return $book->getAuthorId() === $author->getId();
            });
            $author->setBookCount(count($authorBooks));
        }

        return array_values($authors);
    }

    /**
     * Create a new author.
     *
     * @param Author $author
     * @return void
     */
    public function createAuthor(Author $author): void
    {
        $authors = Session::getInstance()->get('authors');
        $id = $this->generateNextId($authors);

        $author->setId($id);
        $authors[$id] = $author;

        Session::getInstance()->set('authors', $authors);
    }

    /**
     * Edit an existing author.
     *
     * @param Author $author
     * @return void
     * @throws Exception
     */
    public function editAuthor(Author $author): void
    {
        $authors = Session::getInstance()->get('authors');
        $id = $author->getId();

        if (!isset($authors[$id])) {
            throw new Exception('Author not found');
        }

        $authors[$id] = $author;

        Session::getInstance()->set('authors', $authors);
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
        $authors = Session::getInstance()->get('authors') ?? [];

        if (!isset($authors[$authorId])) {
            throw new Exception('Author not found');
        }

        unset($authors[$authorId]);
        Session::getInstance()->set('authors', $authors);

        $books = Session::getInstance()->get('books') ?? [];
        $books = array_filter($books, function ($book) use ($authorId) {

            return $book->getAuthorId() !== $authorId;
        });

        Session::getInstance()->set('books', $books);

    }

    /**
     * Get an author by ID.
     *
     * @param int $authorId
     * @return array|null
     */
    public function getAuthorById(int $authorId): ?Author
    {
        $authors = Session::getInstance()->get('authors');
        return $authors[$authorId] ?? null;
    }


    /**
     * Generate next ID.
     *
     * @param array $authors
     * @return int
     */
    private function generateNextId(array $authors): int
    {
        if (empty($authors)) {
            return 1;
        }

        return max(array_keys($authors)) + 1;
    }
}
