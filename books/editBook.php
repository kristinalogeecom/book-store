<?php
// Simulacija baze (npr. knjige iz niza)
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

// Proveri da li je prosleđen ID
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
    <title>Edit Book</title>
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
            background-color: #f9f9f9;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 8px 10px;
            margin-top: 5px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            margin-top: 20px;
            width: 100%;
            background-color: #4a90e2;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #3b7cc4;
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
    </style>
</head>
<body>

<div class="form-container">
    <h2><i class="fa-solid fa-book" style="color: #4a90e2;"></i> Edit Book <span style="font-size: 14px;">(ID: <?= $id ?>)</span></h2>

    <form action="saveBook.php" method="POST" onsubmit="return validateForm()">
        <input type="hidden" name="id" value="<?= $id ?>">

        <label for="title">Book Title:</label>
        <input type="text" id="title" name="title" maxlength="250" required value="<?= htmlspecialchars($book['title']) ?>">

        <label for="year">Year:</label>
        <input type="number" id="year" name="year" required min="-5000" max="999999" value="<?= $book['year'] ?>">

        <input type="submit" value="Save Changes">
    </form>

    <a class="back-link" href="listOfBooks.php"><i class="fa-solid fa-arrow-left"></i> Back to Book List</a>
</div>

<script>
    function validateForm() {
        const year = parseInt(document.getElementById('year').value, 10);
        if (year === 0) {
            alert("Year cannot be 0.");
            return false;
        }
        return true;
    }
</script>

</body>
</html>
