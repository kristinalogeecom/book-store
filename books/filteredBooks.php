<?php

$books = [
    ['id' => 1, 'title' => 'PHP za početnike', 'year' => 2006, 'author' => ['id' => 1, 'name' => 'Pera Peric']],
    ['id' => 2, 'title' => 'Napredni PHP', 'year' => 2011, 'author' => ['id' => 2, 'name' => 'Mika Mikic']],
    ['id' => 3, 'title' => 'PHP i MySQL', 'year' => 2012, 'author' => ['id' => 1, 'name' => 'Pera Peric']],
];

// Uzmi author_id iz URL-a
$authorId = isset($_GET['author_id']) ? (int)$_GET['author_id'] : 0;

// Filtriraj knjige po autoru
$filteredBooks = [];
foreach ($books as $book) {
    if ($book['author']['id'] === $authorId) {
        $filteredBooks[] = $book;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Books by Author</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        table {
            border-collapse: collapse;
            width: 80%;
            margin: 20px auto;
        }

        a {
            color: black;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        th {
            border-bottom: 2px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        td {
            padding: 10px;
            text-align: left;
        }

        .button {
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 4px;
        }

        .edit {
            background-color: #4a90e2;
            color: white;
        }

        .delete {
            background-color: #e94e4e;
            color: white;
        }

        h2 {
            width: 80%;
            margin: 20px auto 10px auto;
            display: flex;
            align-items: center;
        }

        h2 .book-icon {
            color: #4a90e2;
            padding: 8px;
            border-radius: 50%;
            margin-right: 10px;
            font-size: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #4a90e2;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .icon-blue {
            color: #4a90e2;
        }
    </style>
</head>
<body>

<h2>
    <div class="book-icon"><i class="fa-solid fa-book icon-blue"></i></div>
    Books by Author
</h2>

<?php if (count($filteredBooks) > 0): ?>
    <table>
        <tr>
            <th>Title</th>
            <th>Year</th>
        </tr>
        <?php foreach ($filteredBooks as $book): ?>
            <tr>
                <td><?= htmlspecialchars($book['title']) ?></td>
                <td><?= $book['year'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>No books found for this author.</p>
<?php endif; ?>

<a class="back-link" href="listOfBooks.php"><i class="fa-solid fa-arrow-left"></i> Back to Book List</a>

</body>
</html>
