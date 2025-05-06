<?php

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

use BookStore\Controller\AuthorController;
use BookStore\Infrastructure\ServiceRegistry;

$registry = new ServiceRegistry();
$registry->initialize_services();

/** @var AuthorController $authorController */
$authorController = $registry->get(AuthorController::class);

/** @var AuthorController $authorController */
$bookController = $registry->get(AuthorController::class);

$page = $_GET['page'] ?? 'authorsList';

switch ($page) {
    case 'authorsList':
        $authorController->listAuthors()->send();
        break;
    case 'createAuthor':
        $authorController->create_author();
        break;
    case 'editAuthor':
        try {
            $authorController->edit_author($_GET['id'] ?? null);
        } catch (Exception $e) {}
        break;
    case 'deleteAuthor':
        try {
            $authorController->delete_author($_GET['id'] ?? null)->send();
        } catch (Exception $e) {}
        break;
    case 'authorBooks':
        include __DIR__ . '/pages/authorBooks.html';
        break;
    default:
        echo "404 - Page not found";
}
