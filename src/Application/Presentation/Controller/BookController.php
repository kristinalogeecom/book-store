<?php

namespace BookStore\Application\Presentation\Controller;

use BookStore\Application\BusinessLogic\Models\Book;
use BookStore\Application\BusinessLogic\ServiceInterfaces\BookServiceInterface;
use BookStore\Infrastructure\Response\RedirectResponse;
use BookStore\Infrastructure\Response\JsonResponse;
use BookStore\Infrastructure\Response\Response;
use Exception;

class BookController
{
    /**
     * @var BookServiceInterface
     */
    private BookServiceInterface $bookService;

    /**
     * @param BookServiceInterface $bookService
     */
    public function __construct(BookServiceInterface $bookService)
    {
        $this->bookService = $bookService;
    }

    /**
     * Returns all books by the author.
     *
     * @param int $authorId
     * @return Response
     */
    public function getByAuthorId(int $authorId): Response
    {
        try {
            $books = $this->bookService->getByAuthorId($authorId);

            $booksData = array_map(function (Book $book) {
                return [
                    'id' => $book->getId(),
                    'title' => $book->getTitle(),
                    'year' => $book->getYear(),
                    'author_id' => $book->getAuthorId()
                ];
            }, $books);

            return new JsonResponse(['books' => $booksData]);
        } catch (Exception $e) {

            return new RedirectResponse('/index.php?page=error&msg=' . urlencode($e->getMessage()));
        }
    }

    /**
     * Get a book by ID.
     *
     * @param int $bookId
     * @return Response
     */
    public function getBookById(int $bookId): Response
    {
        try {
            $book = $this->bookService->getBookById($bookId);
            if (!$book) {
                return new JsonResponse(['error' => 'Book not found'], 404);
            }
            return new JsonResponse([
                'book' => [
                    'id' => $book->getId(),
                    'title' => $book->getTitle(),
                    'year' => $book->getYear(),
                    'author_id' => $book->getAuthorId()
                ]
            ]);
        } catch (Exception $e) {

            return new RedirectResponse('/index.php?page=error&msg=' . urlencode($e->getMessage()));
        }
    }

    /**
     * Create a new book by the author.
     *
     * @param string $title
     * @param int $year
     * @param int $authorId
     * @return Response
     */
    public function createBook(string $title, int $year, int $authorId): Response
    {
        try {
            $book = new Book(0, $title, $year, $authorId);
            $this->bookService->createBook($book);
            return new JsonResponse(['success' => true], 201);
        } catch (Exception $e) {

            return new RedirectResponse('/index.php?page=error&msg=' . urlencode($e->getMessage()));
        }
    }

    /**
     * Edit an existing book by the author.
     *
     * @param int $bookId
     * @param string $title
     * @param int $year
     * @return Response
     */
    public function editBook(int $bookId, string $title, int $year): Response
    {
        try {
            $book = $this->bookService->getBookById($bookId);

            if (!$book) {
                return new JsonResponse(['error' => 'Book not found'], 404);
            }

            $errors = $this->bookService->validateBookData($title, $year);

            if (!empty($errors)) {
                throw new Exception(implode(' ', $errors));
            }

            $book->setTitle($title);
            $book->setYear($year);

            $this->bookService->editBook($book);

            return new JsonResponse(['success' => true], 200);
        } catch (Exception $e) {

            return new RedirectResponse('/index.php?page=error&msg=' . urlencode($e->getMessage()));
        }
    }

    /**
     * Delete the author's book.
     *
     * @param int $bookId
     * @return Response
     */
    public function deleteBook(int $bookId): Response
    {
        try {
            $this->bookService->deleteBook($bookId);
            return new JsonResponse(['success' => true], 200);
        } catch (Exception $e) {

            return new RedirectResponse('/index.php?page=error&msg=' . urlencode($e->getMessage()));
        }
    }

}