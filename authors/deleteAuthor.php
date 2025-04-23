<?php
// Simulacija baze autora i knjiga
$authors = [
    1 => ['id' => 1, 'name' => 'Pera Peric'],
    2 => ['id' => 2, 'name' => 'Mika Mikic'],
    3 => ['id' => 3, 'name' => 'Zika Zikic'],
    4 => ['id' => 4, 'name' => 'Nikola Nikolic'],
];

$books = [
    ['id' => 1, 'title' => 'PHP za početnike', 'year' => 2006, 'author' => ['id' => 1, 'name' => 'Pera Peric']],
    ['id' => 2, 'title' => 'Napredni PHP', 'year' => 2011, 'author' => ['id' => 2, 'name' => 'Mika Mikic']],
    ['id' => 3, 'title' => 'PHP i MySQL', 'year' => 2012, 'author' => ['id' => 1, 'name' => 'Pera Peric']]
];

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!isset($authors[$id])) {
    echo "Author not found.";
    exit;
}

$author = $authors[$id];

// Prebroj knjige autora
$bookCount = 0;
foreach ($books as $book) {
    if ($book['author']['id'] === $id) {
        $bookCount++;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Author</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .form-container {
            width: 400px;
            margin: 40px auto;
            padding: 30px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #fdf6f6;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #e94e4e;
        }

        .warning-icon {
            color: #e94e4e;
            font-size: 40px;
            display: block;
            text-align: center;
            margin-bottom: 10px;
        }

        .message {
            text-align: center;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .buttons {
            display: flex;
            justify-content: space-between;
        }

        .buttons form {
            flex: 1;
            margin-right: 10px;
        }

        .buttons form:last-child {
            margin-right: 0;
        }

        .delete-button {
            background-color: #e94e4e;
            color: white;
            padding: 10px;
            width: 100%;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        .cancel-button {
            background-color: #ccc;
            color: black;
            padding: 10px;
            width: 100%;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        .cancel-button:hover {
            background-color: #bbb;
        }

        .delete-button:hover {
            background-color: #d63d3d;
        }
    </style>
</head>
<body>

<div class="form-container">
    <div class="warning-icon">
        <i class="fa-solid fa-triangle-exclamation"></i>
    </div>

    <h2>Confirm Delete</h2>
    <div class="message">
        Are you sure you want to delete author <br>
        <strong><?= htmlspecialchars($author['name']) ?></strong>? This will also delete <strong><?= $bookCount ?> book<?= $bookCount !== 1 ? 's' : '' ?></strong> by this author.
    </div>

    <div class="buttons">
        <form action="deleteAuthorAction.php" method="POST">
            <input type="hidden" name="id" value="<?= $author['id'] ?>">
            <button class="delete-button" type="submit">Delete</button>
        </form>
        <form action="listOfAuthors.php" method="GET">
            <button class="cancel-button" type="submit">Cancel</button>
        </form>
    </div>
</div>

</body>
</html>
