<?php

namespace BookStore\Controller;

use BookStore\Response\JsonResponse;
use BookStore\Response\HtmlResponse;
use BookStore\Service\BookService;
use Exception;

class BookController
{
    private BookService $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    public function getByAuthorId(int $authorId): JsonResponse
    {
        try {
            $books = $this->bookService->getByAuthorId($authorId);
            return JsonResponse::json(['books' => $books]);
        } catch (Exception $ex) {
            return JsonResponse::json(['error' => $ex->getMessage()], 500);
        }
    }

    public function getBookById(int $bookId): JsonResponse
    {
        try {
            $book = $this->bookService->getBookById($bookId);
            if (!$book) {
                return JsonResponse::json(['error' => 'Book not found'], 404);
            }
            return JsonResponse::json(['book' => $book]);
        } catch (Exception $e) {
            return JsonResponse::json(['error' => $e->getMessage()], 500);
        }
    }

    public function createBook(string $title, int $year, int $authorId): JsonResponse
    {
        try {
            $this->bookService->createBook($title, $year, $authorId);
            return JsonResponse::json(['success' => true], 201);
        } catch (Exception $e) {
            return JsonResponse::json(['error' => $e->getMessage()], 500);
        }
    }

    public function editBook(int $bookId, string $title, int $year): JsonResponse
    {
        try {
            $this->bookService->editBook($bookId, $title, $year);
            return JsonResponse::json(['success' => true], 200);
        } catch (Exception $e) {
            return JsonResponse::json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteBook(int $bookId): JsonResponse
    {
        try {
            $this->bookService->deleteBook($bookId);
            return JsonResponse::json(['success' => true], 200);
        } catch (Exception $e) {
            return JsonResponse::json(['error' => $e->getMessage()], 500);
        }
    }

}