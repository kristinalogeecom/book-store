<?php

namespace BookStore\Controller;

use BookStore\Response\HtmlResponse;
use BookStore\Response\RedirectResponse;
use BookStore\Response\Response;
use Exception;
use BookStore\Service\AuthorService;

class AuthorController
{
    /**
     * @var AuthorService
     */
    private AuthorService $authorService;

    /**
     * @param AuthorService $authorService
     */
    public function __construct(AuthorService $authorService)
    {
        $this->authorService = $authorService;
    }

    /**
     * List all authors.
     *
     * @return Response
     */
    public function listAuthors(): Response
    {
        $authors = $this->authorService->getAuthors();

        return new HtmlResponse('authorsList', ['authors' => $authors]);
    }

    /**
     * Create a new author.
     *
     * @return Response
     */
    public function createAuthor(): Response
    {
        $errors = ['first_name' => '', 'last_name' => '', 'general' => ''];

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return new HtmlResponse('createAuthor', ['errors' => $errors]);
        }

        $firstName = trim($_POST['first_name'] ?? '');
        $lastName = trim($_POST['last_name'] ?? '');

        try {
            $this->authorService->createAuthor($firstName, $lastName);

            return RedirectResponse::to('index.php?page=authorsList');

        } catch (Exception $e) {
            $this->handleErrorMessages($e, $errors);
            return new HtmlResponse('createAuthor', [
                'errors' => $errors,
                'first_name' => $firstName,
                'last_name' => $lastName
            ]);
        }
    }

    /**
     * Edit an existing author.
     *
     * @param int $id Author ID
     * @return Response
     * @throws Exception if author is not found
     */
    public function editAuthor(int $id): Response
    {
        $errors = ['first_name' => '', 'last_name' => '', 'general' => ''];

        if (!$id) {
            return RedirectResponse::to('index.php?page=authorsList');
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
            return new HtmlResponse('editAuthor', [
                'author' => $author,
                'errors' => $errors,
            ]);
        }

        $firstName = trim($_POST['first_name'] ?? '');
        $lastName = trim($_POST['last_name'] ?? '');

        try {
            $this->authorService->editAuthor($id, $firstName, $lastName);
            return RedirectResponse::to('index.php?page=authorsList');
        } catch (Exception $e) {
            $this->handleErrorMessages($e, $errors);
            return new HtmlResponse('editAuthor', [
                'author' => [
                    'id' => $id,
                    'first_name' => $firstName,
                    'last_name' => $lastName
                ],
                'errors' => $errors,
            ]);
        }
    }

    /**
     * Delete an author.
     *
     * @param int $id Author ID
     * @return Response
     * @throws Exception
     */
    public function deleteAuthor(int $id): Response
    {
        try {
            $author = $this->authorService->getAuthorById($id);
        } catch (Exception $e) {
            $author = null;
        }

        if ($author === null) {
            return RedirectResponse::to('index.php?page=authorsList');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return new HtmlResponse('deleteAuthor', ['author' => $author]);
        }

        try {
            $this->authorService->deleteAuthor($id);
            return RedirectResponse::to('index.php?page=authorsList');
        } catch (Exception $e) {
            return new HtmlResponse('deleteAuthor', [
                'author' => $author,
                'errors' => $e->getMessage()
            ]);
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
}
