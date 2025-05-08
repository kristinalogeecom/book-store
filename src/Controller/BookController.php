<?php

namespace BookStore\Controller;

use BookStore\Response\Response;
use BookStore\Response\JsonResponse;
use BookStore\Service\BookService;
use BookStore\Models\Book;
use Exception;

class BookController
{
    private BookService $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

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
        } catch (Exception $ex) {
            return JsonResponse::json(['error' => $ex->getMessage()], 500);
        }
    }

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
            return JsonResponse::json(['error' => $e->getMessage()], 500);
        }
    }

    public function createBook(string $title, int $year, int $authorId): Response
    {
        try {
            $book = new Book(0, $title, $year, $authorId);
            $this->bookService->createBook($book);
            return JsonResponse::json(['success' => true], 201);
        } catch (Exception $e) {
            return JsonResponse::json(['error' => $e->getMessage()], 500);
        }
    }

    public function editBook(int $bookId, string $title, int $year): Response
    {
        try {
            $book = $this->bookService->getBookById($bookId);

            if(!$book) {
                return JsonResponse::json(['error' => 'Book not found'], 404);
            }

            $book->setTitle($title);
            $book->setYear($year);

            return JsonResponse::json(['success' => true], 200);
        } catch (Exception $e) {
            return JsonResponse::json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteBook(int $bookId): Response
    {
        try {
            $this->bookService->deleteBook($bookId);
            return JsonResponse::json(['success' => true], 200);
        } catch (Exception $e) {
            return JsonResponse::json(['error' => $e->getMessage()], 500);
        }
    }

}