<?php
// Simulacija baze (kao i pre)
$books = [
    [
        'id' => 1,
        'title' => 'PHP za početnike',
        'year' => 2006,
        'author' => ['id' => 1, 'name' => 'Pera Peric']
    ],
    [
        'id' => 2,
        'title' => 'Napredni PHP',
        'year' => 2011,
        'author' => ['id' => 2, 'name' => 'Mika Mikic']
    ],
    [
        'id' => 3,
        'title' => 'PHP i MySQL',
        'year' => 2012,
        'author' => ['id' => 1, 'name' => 'Pera Peric']
    ]
];

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$book = null;
foreach ($books as $b) {
    if ($b['id'] === $id) {
        $book = $b;
        break;
    }
}

if (!$book) {
    echo "Book not found.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Book</title>
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
        You are about to delete the book <br><strong><?= htmlspecialchars($book['title']) ?> (<?= $book['year'] ?>)</strong>.
        If you procees with this action Application will permanently delete this book.
    </div>

    <div class="buttons">
        <form action="deleteBookAction.php" method="POST">
            <input type="hidden" name="id" value="<?= $book['id'] ?>">
            <button class="delete-button" type="submit">Delete</button>
        </form>
        <form action="listOfBooks.php" method="GET">
            <button class="cancel-button" type="submit">Cancel</button>
        </form>
    </div>
</div>

</body>
</html>
