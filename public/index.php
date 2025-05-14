<?php

require_once __DIR__ . '/../vendor/autoload.php';

try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../', ['.env.local', '.env']);
    $dotenv->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
} catch (Dotenv\Exception\InvalidFileException $e) {
    error_log("Critical error: Invalid .env file format: " . $e->getMessage());
}

use BookStore\Application\Presentation\Controller\AuthorController;
use BookStore\Application\Presentation\Controller\BookController;
use BookStore\Application\Presentation\Controller\ErrorController;
use BookStore\Infrastructure\Container\ServiceRegistry;
use BookStore\Infrastructure\Response\JsonResponse;
use BookStore\Application\Configuration\DependencyConfigurator;

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
                $bookController->getAllBooksForAuthor((int)$authorId)->send();
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
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $authorController->createAuthor($_POST['firstName'] ?? '', $_POST['lastName'] ?? '')->send();
            } else {
                $authorController->showCreateForm()->send();
            }
            break;
        case 'editAuthor':
            $id = (int)($_GET['id'] ?? 0);
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                $authorController->editAuthor($id, $_POST['firstName'] ?? '', $_POST['lastName'] ?? '')->send();
            } else {
                $authorController->showEditForm($id)->send();
            }
            break;
        case 'deleteAuthor':
            $id = (int)($_GET['id'] ?? 0);
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $authorController->deleteAuthor($id)->send();
            } else {
                $authorController->showDeleteForm($id)->send();
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
