<?php

require_once __DIR__ . '/../vendor/autoload.php';

use BookStore\Controller\AuthorController;
use BookStore\Infrastructure\ServiceRegistry;

$registry = new ServiceRegistry();

$controller = $registry->get(AuthorController::class);

$page = $_GET['page'] ?? 'authorsList';

switch ($page) {
    case 'authorsList':
        $controller->list_authors();
        break;
    case 'createAuthor':
        $controller->create_author();
        break;
    case 'editAuthor':
        try {
            $controller->edit_author($_GET['id'] ?? null);
        } catch (Exception $e) {}
        break;
    case 'deleteAuthor':
        try {
            $controller->delete_author($_GET['id'] ?? null);
        } catch (Exception $e) {}
        break;
    default:
        echo "404 - Page not found";
}
