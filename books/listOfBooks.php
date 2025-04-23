<?php

$books = [
    ['id' => 1, 'title' => 'PHP za početnike', 'year' => 2006, 'author' => ['id' => 1, 'name' => 'Pera Peric']],
    ['id' => 2, 'title' => 'Napredni PHP', 'year' => 2011, 'author' => ['id' => 2, 'name' => 'Mika Mikic']],
    ['id' => 3, 'title' => 'PHP i MySQL', 'year' => 2012, 'author' => ['id' => 1, 'name' => 'Pera Peric']],
];


?>

<!DOCTYPE html>
<html>
<head>
    <title>Book List</title>
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

        a.button {
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

        .add {
            background-color: #4a90e2;
            color: white;
            text-decoration: none;
        }

        h2 .add i {
            font-size: 16px;
            padding: 6px 10px;
            background-color: #4a90e2;
            color: white;
            border-radius: 4px;
            text-decoration: none;
        }
    </style>
</head>
<body>

<h2 style="width: 80%; margin: 20px auto 10px auto">
    Book List
    <a class="add" href="createBook.php"><i class="fa-solid fa-plus"></i></a>
</h2>

<table>
    <tr>
        <th>Book</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($books as $book): ?>
        <tr>
            <td><?= htmlspecialchars($book['title']) ?> (<?= $book['year'] ?>)</td>
            <td>
                <a class="button edit" href="editBook.php?id=<?= $book['id'] ?>"><i class="fa-solid fa-pen-to-square"></i></a>
                <a class="button delete" href="deleteBook.php?id=<?= $book['id'] ?>"><i class="fa-solid fa-trash"></i></a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>