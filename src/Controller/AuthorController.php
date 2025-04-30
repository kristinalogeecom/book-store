<?php

namespace BookStore\Controller;

use Exception;
use BookStore\Service\AuthorService;

class AuthorController
{
    /**
     * @var AuthorService
     */
    private AuthorService $author_service;

    /**
     * @param AuthorService $author_service
     */
    public function __construct(AuthorService $author_service)
    {
        $this->author_service = $author_service;
    }

    /**
     * List all authors.
     *
     * @return void
     */
    public function list_authors(): void
    {
        $authors = $this->author_service->get_authors();
        include __DIR__ . '/../../public/pages/authorsList.phtml';
    }

    /**
     * Create a new author.
     *
     * @return void
     */
    public function create_author(): void
    {
        $errors = [
            'first_name' => '',
            'last_name' => '',
            'general' => '',
        ];

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            include __DIR__ . '/../../public/pages/createAuthor.phtml';

            return;
        }

        $first_name = trim($_POST['first_name'] ?? '');
        $last_name = trim($_POST['last_name'] ?? '');

        try {
            $this->author_service->create_author($first_name, $last_name);
            header('Location: index.php?page=authorsList');
            exit;
        } catch (Exception $e) {
            $this->handle_error_messages($e, $errors);
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
    public function edit_author(int $id): void
    {
        $errors = [
            'first_name' => '',
            'last_name' => '',
            'general' => '',
        ];

        if (!$id) {
            header('Location: index.php?page=authorsList');
            exit;
        }

        try {
            $author = $this->author_service->get_author_by_id($id);
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
            $this->author_service->edit_author($id, $first_name, $last_name);
            header('Location: index.php?page=authorsList');
            exit;
        } catch (Exception $e) {
            $this->handle_error_messages($e, $errors);
            include __DIR__ . '/../../public/pages/editAuthor.phtml';

            return;
        }
    }

    /**
     * Delete an author.
     *
     * @param int $id Author ID
     * @return void
     * @throws Exception
     */
    public function delete_author(int $id): void
    {
        $author = null;

        try {
            $author = $this->author_service->get_author_by_id($id);
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
            $this -> author_service -> delete_author($id);
            header('Location: index.php?page=authorsList');
            exit;

        } catch (Exception $e) {
            $errors = $e->getMessage();
            include __DIR__ . '/../../public/pages/deleteAuthor.phtml';

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
    private function handle_error_messages(Exception $e, array &$errors): void
    {
        $error_messages = json_decode($e->getMessage(), true);

        $errors['first_name'] = $error_messages['first_name'] ?? '';
        $errors['last_name'] = $error_messages['last_name'] ?? '';

        if (empty($errors['first_name']) && empty($errors['last_name'])) {
            $errors['general'] = $error_messages['general'] ?? 'An error occurred during the process.';
        }
    }
}
