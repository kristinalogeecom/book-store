<?php
require_once __DIR__ . '/../src/Controller/AuthorController.php';   // PHP files that define classes are included
require_once __DIR__ . '/../src/Service/AuthorService.php';     // __DIR__ indicates the current directory where index.php is located
require_once __DIR__ . '/../src/Repository/AuthorRepository.php';

use Controller\AuthorController;    // shorter usage of the full Controller\AuthorController class

$controller = new AuthorController();

$page = $_GET['page'] ?? 'authorsList';     // if no page parameters are passed, the default value 'authorsList' is used

switch ($page) {
    case 'authorsList':
        $controller->listAuthors();
        break;
    case 'createAuthor':
        $controller->createAuthor();
        break;
    case 'editAuthor':
        $controller->editAuthor($_GET['id'] ?? null);     // passing id from url; if id is not set, null is passed
        break;
    case 'deleteAuthor':
        $controller->deleteAuthor($_GET['id'] ?? null);
        break;
    default:
        echo "404 - Page not found";
}
