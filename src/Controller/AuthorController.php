<?php

namespace BookStore\Controller;

use Exception;
use BookStore\Service\AuthorService;
use BookStore\Repository\AuthorRepository;
session_start();

class AuthorController
{
    /**
     * @var AuthorService
     */
    private AuthorService $authorService;

    public function __construct()
    {
        $repository = new AuthorRepository();
        $this->authorService = new AuthorService($repository);
    }

    /**
     * List all authors.
     *
     * @return void
     */
    public function listAuthors(): void
    {
        $authors = $this->authorService->getAuthors();
        include __DIR__ . '/../../public/pages/authorsList.phtml';
    }

    /**
     * Create a new author.
     *
     * @return void
     */
    public function createAuthor(): void
    {
        $errors = [
            'first_name' => '',
            'last_name' => '',
            'general' => '',
        ];

        $first_name = '';
        $last_name = '';

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            include __DIR__ . '/../../public/pages/createAuthor.phtml';
            return;
        }

        $first_name = trim($_POST['first_name'] ?? '');
        $last_name = trim($_POST['last_name'] ?? '');

        try {
            $this->authorService->createAuthor($first_name, $last_name);
            header('Location: index.php?page=authorsList');
            exit;
        } catch (Exception $e) {
            $this->handleErrorMessages($e, $errors);

            include __DIR__ . '/../../public/pages/createAuthor.phtml';
            return;
        }
    }

    /**
     * Edit an existing author.
     *
     * @param int $id Author ID
     * @return void
     * @throws Exception if author is not found
     */
    public function editAuthor(int $id): void
    {
        $errors = [
            'first_name' => '',
            'last_name' => '',
            'general' => '',
        ];

        $first_name = '';
        $last_name = '';

        if (!$id) {
            header('Location: index.php?page=authorsList');
            exit;
        }

        try {
            $author = $this->authorService->getAuthorById($id);
            if (!$author) {
                throw new Exception('Author not found.');
            }
        } catch (Exception $e) {
            $errors['general'] = $e->getMessage();
            $author = null;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            if($author) {
                $first_name = $author['first_name'];
                $last_name = $author['last_name'];
            }
            include __DIR__ . '/../../public/pages/editAuthor.phtml';
            return;
        }

        $first_name = trim($_POST['first_name'] ?? '');
        $last_name = trim($_POST['last_name'] ?? '');

        try {
            $this->authorService->editAuthor($id, $first_name, $last_name);
            header('Location: index.php?page=authorsList');
            exit;
        } catch (Exception $e) {
            $this->handleErrorMessages($e, $errors);
            include __DIR__ . '/../../public/pages/editAuthor.phtml';
            return;
        }
    }

    /**
     * Handle error messages for author creating/editing.
     *
     * @param Exception $e
     * @param array $errors Error messages array
     * @return void
     */
    private function handleErrorMessages(Exception $e, array &$errors): void
    {
        $errorMessages = json_decode($e->getMessage(), true);

        $errors['first_name'] = $errorMessages['first_name'] ?? '';
        $errors['last_name'] = $errorMessages['last_name'] ?? '';

        if (empty($errors['first_name']) && empty($errors['last_name'])) {
            $errors['general'] = $errorMessages['general'] ?? 'An error occurred during the process.';
        }
    }

    /**
     * Delete an author.
     *
     * @param int $id Author ID
     * @return void
     * @throws Exception
     */
    public function deleteAuthor(int $id): void
    {
        $author = null;

        try {
            $author = $this->authorService->getAuthorById($id);
        } catch (Exception $e) {}

        if ($author === null) {
            header('Location: index.php?page=authorsList');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            include __DIR__ . '/../../public/pages/deleteAuthor.phtml';
            return;
        }

        try {
            $this -> authorService -> deleteAuthor($id);
            header('Location: index.php?page=authorsList');
            exit;

        } catch (Exception $e) {
            $errors = $e->getMessage();
            include __DIR__ . '/../../public/pages/deleteAuthor.phtml';
            return;
        }
    }
}
