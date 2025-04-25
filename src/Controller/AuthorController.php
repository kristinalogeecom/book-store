<?php

namespace Controller;   // if we have 2 classes with the same name

use Exception;
use Service\AuthorService;
use Repository\AuthorRepository;
session_start();

class AuthorController
{
    private AuthorService $authorService;

    public function __construct()
    {
        $repository = new AuthorRepository();
        $this->authorService = new AuthorService($repository);
    }

    public function listAuthors(): void
    {
        $authors = $this->authorService->getAuthors();
        //$authors = $this->service->getAuthorsWithBookCount();
        include __DIR__ . '/../../public/authorsList.phtml';
    }

    public function createAuthor(): void
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $first_name = trim($_POST['first_name'] ?? '');
            $last_name = trim($_POST['last_name'] ?? '');

            $errors = [];

            if(empty($first_name)) {
                $errors[] = 'First name is required';
            }

            if(empty($last_name)) {
                $errors[] = 'Last name is required';
            }

            if(empty($errors)) {
                try {
                    $this->authorService->createAuthor($first_name, $last_name);
                    header('Location: index.php?page=authorsList');
                    exit;
                } catch (Exception $e) {
                    $errors[] = $e->getMessage();
                }
            }

            $error = implode('<br>', $errors);

        }
        include __DIR__ . '/../../public/createAuthor.phtml';
    }

    public function editAuthor(mixed $id) {

        $author = $this->authorService->getAuthorById($id);
        if ($author === null) {
            header('Location: index.php?page=authorsList');
            exit;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $first_name = trim($_POST['first_name'] ?? '');
            $last_name = trim($_POST['last_name'] ?? '');

            $errors = [];

            if(empty($first_name)) {
                $errors[] = 'First name is required';
            }

            if(empty($last_name)) {
                $errors[] = 'Last name is required';
            }

            if(empty($errors)) {
                try {
                    $this->authorService->editAuthor($id, $first_name, $last_name);
                    header('Location: index.php?page=authorsList');
                    exit;
                } catch (Exception $e) {
                    $errors[] = $e->getMessage();
                }
            }

            $error = implode('<br>', $errors);

        }
        include __DIR__ . '/../../public/editAuthor.phtml';
    }

    public function deleteAuthor(mixed $id)
    {
        $author = $this->authorService->getAuthorById($id);
        if ($author === null) {
            header('Location: index.php?page=authorsList');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this -> authorService -> deleteAuthor($id);
                header('Location: index.php?page=authorsList');
                exit;
            } catch (Exception $e) {
                $errors = $e->getMessage();
            }
        }
        include __DIR__ . '/../../public/deleteAuthor.phtml';
    }
}
