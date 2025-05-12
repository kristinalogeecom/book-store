<?php

namespace BookStore\Application\Presentation\Controller;

use BookStore\Application\BusinessLogic\Models\Author;
use BookStore\Application\BusinessLogic\ServiceInterfaces\AuthorServiceInterface;
use BookStore\Infrastructure\Response\HtmlResponse;
use BookStore\Infrastructure\Response\RedirectResponse;
use BookStore\Infrastructure\Response\Response;
use BookStore\Application\BusinessLogic\Service\AuthorService;
use Exception;

class AuthorController
{
    /**
     * @var AuthorServiceInterface
     */
    private AuthorServiceInterface $authorService;

    /**
     * @param AuthorServiceInterface $authorService
     */
    public function __construct(AuthorServiceInterface $authorService)
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
     * @return Response
     */
    public function showCreateForm(): Response
    {
        return new HtmlResponse('createAuthor', [
            'errors' => [],
            'firstName' => '',
            'lastName' => ''
        ]);
    }

    /**
     * Create new author.
     *
     * @param string $firstName
     * @param string $lastName
     * @return Response
     */
    public function createAuthor(string $firstName, string $lastName): Response
    {
        try {
            $author = new Author(0, $firstName, $lastName);
            $this->authorService->createAuthor($author);

            return new RedirectResponse('index.php?page=authorsList');

        } catch (Exception $e) {
            $errors = $this->handleErrorMessages($e->getMessage());

            return new HtmlResponse('createAuthor', [
                'errors' => $errors,
                'firstName' => $firstName,
                'lastName' => $lastName
            ]);
        }
    }

    /**
     * @param int $id
     * @return Response
     */
    public function showEditForm(int $id): Response
    {
        try {
            $author = $this->authorService->getAuthorById($id);

            return new HtmlResponse('editAuthor', [
                'errors' => [],
                'author' => $author
            ]);
        } catch (Exception $e) {

            return new HtmlResponse('editAuthor', [
                'errors' => ['general' => $e->getMessage()],
                'author' => new Author($id, '', '')
            ]);
        }
    }

    /**
     * Edit an existing author.
     *
     * @param int $id
     * @param string $firstName
     * @param string $lastName
     * @return Response
     */
    public function editAuthor(int $id, string $firstName, string $lastName): Response
    {
        $author = new Author($id, $firstName, $lastName);

        try {
            $this->authorService->editAuthor($author);

            return new RedirectResponse('index.php?page=authorsList');
        } catch (Exception $e) {
            $errors = $this->handleErrorMessages($e->getMessage());

            return new HtmlResponse('editAuthor', [
                'author' => $author,
                'errors' => $errors,
            ]);
        }
    }

    public function showDeleteForm(int $id): Response
    {
        try {
            $author = $this->authorService->getAuthorById($id);
            return new HtmlResponse('deleteAuthor', [
                'author' => $author,
                'errors' => []
            ]);
        } catch (Exception $e) {
            return new HtmlResponse('deleteAuthor', [
                'author' => new Author($id, '', ''),
                'errors' => ['general' => $e->getMessage()]
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
            $this->authorService->deleteAuthor($id);

            return new RedirectResponse('index.php?page=authorsList');
        } catch (Exception $e) {

            return new HtmlResponse('deleteAuthor', [
                'author' => new Author($id, '', ''),
                'errors' => $e->getMessage()
            ]);
        }
    }

    public function renderAuthorBooksPage(): HtmlResponse
    {
        return new HtmlResponse(__DIR__ . '/../Pages/authorBooks.html');
    }

    /**
     * Handle error messages for author creating/editing.
     *
     * @param string $jsonMessage
     * @return array
     */
    private function handleErrorMessages(string $jsonMessage): array
    {
        $errorMessages = json_decode($jsonMessage, true) ?: [];

        return [
            'firstName' => $errorMessages['firstName'] ?? '',
            'lastName' => $errorMessages['lastName'] ?? '',
            'general' => $errorMessages['general'] ?? 'An error occurred.',
        ];
    }
}
