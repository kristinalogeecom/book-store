<?php

require_once __DIR__ . '/../vendor/autoload.php';

use BookStore\Application\Presentation\Controller\AuthorController;
use BookStore\Application\Presentation\Controller\BookController;
use BookStore\Application\Presentation\Controller\ErrorController;
use BookStore\Infrastructure\Container\ServiceRegistry;
use BookStore\Infrastructure\Response\JsonResponse;
use BookStore\Infrastructure\Container\DependencyConfigurator;

try {
    DependencyConfigurator::configure();

    /** @var AuthorController $authorController */
    $authorController = ServiceRegistry::get(AuthorController::class);

    /** @var BookController $bookController */
    $bookController = ServiceRegistry::get(BookController::class);

    /** @var ErrorController $errorController */
    $errorController = ServiceRegistry::get(ErrorController::class);

    if (isset($_GET['api']) && $_GET['api'] === 'books') {
        header('Content-Type: application/json');

        $method = $_SERVER['REQUEST_METHOD'];
        $data = json_decode(file_get_contents('php://input'), true) ?? [];

        $action = $_GET['action'] ?? '';
        $authorId = $_GET['author_id'] ?? $data['author_id'] ?? null;
        $bookId = $_GET['book_id'] ?? $data['id'] ?? null;

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
                return new JsonResponse(['error' => 'Invalid API action'], 400);
        }
        exit;
    }

    $page = $_GET['page'] ?? 'authorsList';

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
            $authorController->renderAuthorBooksPage()->send();
            break;
        case 'error':
            $msg = $_GET['msg'] ?? 'Unknown error occurred.';
            $errorController->render($msg)->send();
            break;
        default:
            echo "404 - Page not found";
    }

} catch (Exception $e) {
    return new JsonResponse(['error' => $e->getMessage()], 500);
}
