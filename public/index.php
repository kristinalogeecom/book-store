<?php

require_once __DIR__ . '/../vendor/autoload.php';

use BookStore\Controller\AuthorController;

$controller = new AuthorController();
$page = $_GET['page'] ?? 'authorsList';

switch ($page) {
    case 'authorsList':
        $controller->listAuthors();
        break;
    case 'createAuthor':
        $controller->createAuthor();
        break;
    case 'editAuthor':
        try {
            $controller->editAuthor($_GET['id'] ?? null);
        } catch (Exception $e) {

        }
        break;
    case 'deleteAuthor':
        try {
            $controller->deleteAuthor($_GET['id'] ?? null);
        } catch (Exception $e) {

        }
        break;
    default:
        echo "404 - Page not found";
}
