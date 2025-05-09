<?php

require_once __DIR__ . '/../vendor/autoload.php';

use BookStore\Application\Presentation\Controller\AuthorController;
use BookStore\Application\Presentation\Controller\BookController;
use BookStore\Infrastructure\Container\ServiceRegistry;
use BookStore\Infrastructure\Session\Session;
use BookStore\Infrastructure\Response\JsonResponse;

try {
    ServiceRegistry::initializeServices();
    Session::getInstance();
} catch (Exception $e) {
    JsonResponse::json(['error' => 'Failed to initialize services'], 500)->send();
    exit;
}

/** @var AuthorController $authorController */
/** @var BookController $bookController */
try {
    $authorController = ServiceRegistry::get(AuthorController::class);
    $bookController = ServiceRegistry::get(BookController::class);
} catch (Exception $e) {
    JsonResponse::json(['error' => 'Failed to load controllers'], 500)->send();
    exit;
}

if (isset($_GET['api']) && $_GET['api'] === 'books') {
    header('Content-Type: application/json');

    $method = $_SERVER['REQUEST_METHOD'];
    $data = json_decode(file_get_contents('php://input'), true) ?? [];

    $action = $_GET['action'] ?? '';
    $authorId = $_GET['author_id'] ?? $data['author_id'] ?? null;
    $bookId = $_GET['book_id'] ?? $data['id'] ?? null;

    try {
        switch ($action) {
            case 'getByAuthor':
                $bookController->getByAuthorId((int)$authorId)->send();
                break;
            case 'getById':
                $bookController->getBookById((int)$bookId)->send();
                break;
            case 'create':
                $bookController->createBook(
                    $data['title'] ?? '',
                    (int)$data['year'],
                    (int)$authorId
                )->send();
                break;
            case 'edit':
                $bookController->editBook(
                    (int)$bookId,
                    $data['title'] ?? '',
                    (int)$data['year']
                )->send();
                break;
            case 'delete':
                $bookController->deleteBook((int)$bookId)->send();
                break;
            default:
                JsonResponse::json(['error' => 'Invalid API action'], 400)->send();
        }
    } catch (Exception $e) {
        JsonResponse::json(['error' => $e->getMessage()], 500)->send();
    }
    exit;
}

$page = $_GET['page'] ?? 'authorsList';

try {
    switch ($page) {
        case 'authorsList':
            $authorController->listAuthors()->send();
            break;
        case 'createAuthor':
            $authorController->createAuthor()->send();
            break;
        case 'editAuthor':
        case 'deleteAuthor':
            $id = (int)($_GET['id'] ?? 0);
            if ($page === 'editAuthor') {
                $authorController->editAuthor($id)->send();
            } else {
                $authorController->deleteAuthor($id)->send();
            }
            break;
        case 'authorBooks':
            include __DIR__ . '../../src/Application/Presentation/Pages/authorBooks.html';
            break;
        case 'error':
            include __DIR__ . '/../src/Application/Presentation/Pages/error.phtml';
            break;
        default:
            echo "404 - Page not found";
    }
} catch (Exception $e) {
    JsonResponse::json(['error' => $e->getMessage()], 500)->send();
}