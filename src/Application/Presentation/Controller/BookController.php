<?php

namespace BookStore\Application\Presentation\Controller;

use BookStore\Application\BusinessLogic\Models\Book;
use BookStore\Infrastructure\Response\RedirectResponse;
use BookStore\Infrastructure\Response\JsonResponse;
use BookStore\Infrastructure\Response\Response;
use BookStore\Application\BusinessLogic\Service\BookService;
use Exception;

class BookController
{
    private BookService $bookService;

    /**
     * @param BookService $bookService
     */
    public function __construct(BookService $bookService)
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

            return JsonResponse::json(['books' => $booksData]);
        } catch (Exception $e) {

            return RedirectResponse::to('/Pages/error.phtml?msg=' . urlencode($e->getMessage()));
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
                return JsonResponse::json(['error' => 'Book not found'], 404);
            }
            return JsonResponse::json([
                'book' => [
                    'id' => $book->getId(),
                    'title' => $book->getTitle(),
                    'year' => $book->getYear(),
                    'author_id' => $book->getAuthorId()
                ]
            ]);
        } catch (Exception $e) {

            return RedirectResponse::to('/Pages/error.phtml?msg=' . urlencode($e->getMessage()));
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
            return JsonResponse::json(['success' => true], 201);
        } catch (Exception $e) {

            return RedirectResponse::to('/Pages/error.phtml?msg=' . urlencode($e->getMessage()));
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
                return JsonResponse::json(['error' => 'Book not found'], 404);
            }

            $errors = $this->bookService->validateBookData($title, $year);

            if (!empty($errors)) {
                throw new Exception(implode(' ', $errors));
            }

            $book->setTitle($title);
            $book->setYear($year);

            $this->bookService->editBook($book);

            return JsonResponse::json(['success' => true], 200);
        } catch (Exception $e) {

            return RedirectResponse::to('/Pages/error.phtml?msg=' . urlencode($e->getMessage()));
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
            return JsonResponse::json(['success' => true], 200);
        } catch (Exception $e) {

            return RedirectResponse::to('/Pages/error.phtml?msg=' . urlencode($e->getMessage()));
        }
    }

}