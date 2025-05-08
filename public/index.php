<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

use BookStore\Controller\AuthorController;
use BookStore\Controller\BookController;
use BookStore\Infrastructure\ServiceRegistry;
use BookStore\Response\JsonResponse;
use BookStore\Infrastructure\Session;


try {
    ServiceRegistry::initializeServices();
} catch (Exception $e) {

}
Session::getInstance();

/** @var AuthorController $authorController */
try {
    $authorController = ServiceRegistry::get(AuthorController::class);
} catch (Exception $e) {

}
/** @var BookController $bookController */
try {
    $bookController = ServiceRegistry::get(BookController::class);
} catch (Exception $e) {

}

if (isset($_GET['api']) && $_GET['api'] === 'books') {
    header('Content-Type: application/json');

    $method = $_SERVER['REQUEST_METHOD'];
    $data = json_decode(file_get_contents('php://input'), true) ?? [];

    $authorId = $_GET['author_id'] ?? $data['author_id'] ?? null;
    $bookId = $_GET['book_id'] ?? $data['id'] ?? null;

    try {
        switch ($_GET['action'] ?? '') {
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
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            $authorController->editAuthor($id)->send();
            break;
        case 'deleteAuthor':
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            $authorController->deleteAuthor($id)->send();
            break;
        case 'authorBooks':
            include __DIR__ . '/pages/authorBooks.html';
            break;
        default:
            echo "404 - Page not found";
    }
} catch (Exception $e) {
    echo "An unexpected error occurred: " . htmlspecialchars($e->getMessage());
}
