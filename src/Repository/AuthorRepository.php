<?php

namespace Repository;

use Exception;

class AuthorRepository
{
    public function getAllAuthors(): array
    {
        session_start();
        if (!isset($_SESSION['authors'])) {
            $_SESSION['authors'] = [];
        }
        return $_SESSION['authors'];
    }

    public function getAllBooks(): array
    {
        if (!isset($_SESSION['books'])) {
            $_SESSION['books'] = [];
        }
        return $_SESSION['books'];
    }

    public function createAuthor($first_name, $last_name): void
    {
        session_start();
        if(!isset($_SESSION['authors'])) {
            $_SESSION['authors'] = [];
        }

        $authors = $_SESSION['authors'];
        $newID = count($authors) > 0 ? max(array_column($authors, 'id')) + 1 : 1;

        $authors[] = [
            'id' => $newID,
            'first_name' => $first_name,
            'last_name' => $last_name,
        ];

        $_SESSION['authors'] = $authors;
    }

    /**
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
     * @throws Exception
     */
    public function getAuthorById(int $id)
    {
        $authors = $this->getAllAuthors();
        foreach ($authors as $author) {
            if ($author['id'] === $id) {
                return $author;
            }
        }
        return null;
    }

    public function deleteAuthor(int $id)
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
