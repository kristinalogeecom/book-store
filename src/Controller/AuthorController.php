<?php

namespace BookStore\Controller;

use BookStore\Response\HtmlResponse;
use BookStore\Response\RedirectResponse;
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
        HtmlResponse::view('authorsList', ['authors' => $authors])->send();
    }

    /**
     * Create a new author.
     *
     * @return void
     */
    public function create_author(): void
    {
        $errors = ['first_name' => '', 'last_name' => '', 'general' => ''];

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            HtmlResponse::view('createAuthor', ['errors'=>$errors])->send();

            return;
        }

        $first_name = trim($_POST['first_name'] ?? '');
        $last_name = trim($_POST['last_name'] ?? '');

        try {
            $this->author_service->create_author($first_name, $last_name);
            RedirectResponse::to('index.php?page=authorsList')->send();
        } catch (Exception $e) {
            $this->handle_error_messages($e, $errors);
            HtmlResponse::view('createAuthor', [
                'errors' => $errors,
                'first_name' => $first_name,
                'last_name' => $last_name
            ])->send();
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
        $errors = ['first_name' => '', 'last_name' => '', 'general' => ''];

        if (!$id) {
            RedirectResponse::to('index.php?page=authorsList')->send();

            return;
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
            HtmlResponse::view('editAuthor', [
                'author' => $author,
                'errors' => $errors,
            ])->send();
            return;
        }

        $first_name = trim($_POST['first_name'] ?? '');
        $last_name = trim($_POST['last_name'] ?? '');

        try {
            $this->author_service->edit_author($id, $first_name, $last_name);
            RedirectResponse::to('index.php?page=authorsList')->send();
        } catch (Exception $e) {
            $this->handle_error_messages($e, $errors);
            HtmlResponse::view('editAuthor', [
                'author' => [
                    'id' => $id,
                    'first_name' => $first_name,
                    'last_name' => $last_name
                ],
                'errors' => $errors,
            ])->send();
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
        try {
            $author = $this->author_service->get_author_by_id($id);
        } catch (Exception $e) {
            $author = null;
        }

        if ($author === null) {
            RedirectResponse::to('index.php?page=authorsList')->send();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            HtmlResponse::view('deleteAuthor', ['author' => $author])->send();
            return;
        }

        try {
            $this -> author_service -> delete_author($id);
            RedirectResponse::to('index.php?page=authorsList')->send();
        } catch (Exception $e) {
            HtmlResponse::view('deleteAuthor', [
                'author' => $author,
                'errors' => $e->getMessage()
            ])->send();
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
