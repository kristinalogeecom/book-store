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
        include __DIR__ . '/../../public/pages/authorsList.phtml';
    }

    public function createAuthor(): void
    {
        $errors = [
            'first_name' => '',
            'last_name' => '',
            'general' => '',
        ];

        $first_name = '';
        $last_name = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $first_name = trim($_POST['first_name'] ?? '');
            $last_name = trim($_POST['last_name'] ?? '');

            if (empty($first_name)) {
                $errors['first_name'] = 'First name is required';
            }

            if (empty($last_name)) {
                $errors['last_name'] = 'Last name is required';
            }

            if (empty($errors['first_name']) && empty($errors['last_name'])) {
                try {
                    $this->authorService->createAuthor($first_name, $last_name);
                    header('Location: index.php?page=authorsList');
                    exit;
                } catch (Exception $e) {
                    $message = $e->getMessage();

                    // Pametno raspoređivanje poruka
                    if (str_contains($message, 'First name')) {
                        $errors['first_name'] = $message;
                    } elseif (str_contains($message, 'Last name')) {
                        $errors['last_name'] = $message;
                    } else {
                        $errors['general'] = $message;
                    }
                }
            }
        }

        include __DIR__ . '/../../public/pages/createAuthor.phtml';
    }



    public function editAuthor(): void
    {
        $errors = [
            'first_name' => '',
            'last_name' => '',
            'general' => '',
        ];

        $first_name = '';
        $last_name = '';

        $id = (int)($_GET['id'] ?? 0);

        if (!$id) {
            // Nevalidan ID - možeš baciti grešku ili preusmeriti
            header('Location: index.php?page=authorsList');
            exit;
        }

        try {
            $author = $this->authorService->getAuthorById($id);
            if (!$author) {
                throw new Exception('Author not found.');
            }
        } catch (Exception $e) {
            $errors['general'] = $e->getMessage();
            $author = null;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $first_name = trim($_POST['first_name'] ?? '');
            $last_name = trim($_POST['last_name'] ?? '');

            if (empty($first_name)) {
                $errors['first_name'] = 'First name is required';
            }

            if (empty($last_name)) {
                $errors['last_name'] = 'Last name is required';
            }

            if (empty($errors['first_name']) && empty($errors['last_name'])) {
                try {
                    $this->authorService->editAuthor($id, $first_name, $last_name);
                    header('Location: index.php?page=authorsList');
                    exit;
                } catch (Exception $e) {
                    $message = $e->getMessage();
                    if (str_contains($message, 'First name')) {
                        $errors['first_name'] = $message;
                    } elseif (str_contains($message, 'Last name')) {
                        $errors['last_name'] = $message;
                    } else {
                        $errors['general'] = $message;
                    }
                }
            }
        } else {
            // Ako je GET zahtev, punimo formu sa postojećim podacima
            if ($author) {
                $first_name = $author['first_name'];
                $last_name = $author['last_name'];
            }
        }

        include __DIR__ . '/../../public/pages/editAuthor.phtml';
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
        include __DIR__ . '/../../public/pages/deleteAuthor.phtml';
    }
}
