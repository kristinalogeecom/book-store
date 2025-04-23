<?php

// Simulacija baze autora
$authors = [
    1 => ['id' => 1, 'name' => 'Pera Peric'],
    2 => ['id' => 2, 'name' => 'Mika Mikic'],
    3 => ['id' => 3, 'name' => 'Zika Zikic'],
    4 => ['id' => 4, 'name' => 'Nikola Nikolic'],
];

// Proveri da li je prosleđen ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!isset($authors[$id])) {
    echo "Author not found.";
    exit;
}

$author = $authors[$id];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Author</title>
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

        input[type="text"] {
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
    <h2><i class="fa-solid fa-user" style="color: #4a90e2;"></i> Edit Author <span style="font-size: 14px;">(ID: <?= $id ?>)</span></h2>

    <form action="saveAuthor.php" method="POST" onsubmit="return validateForm()">
        <input type="hidden" name="id" value="<?= $id ?>">

        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" maxlength="100" required value="<?= htmlspecialchars(explode(" ", $author['name'])[0]) ?>">

        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" maxlength="100" required value="<?= htmlspecialchars(explode(" ", $author['name'])[1]) ?>">

        <input type="submit" value="Save Changes">
    </form>

    <a class="back-link" href="listOfAuthors.php"><i class="fa-solid fa-arrow-left"></i> Back to Author List</a>
</div>

<script>
    function validateForm() {
        const firstName = document.getElementById('first_name').value.trim();
        const lastName = document.getElementById('last_name').value.trim();

        if (firstName.length === 0 || lastName.length === 0) {
            alert("First and last names are required.");
            return false;
        }

        if (firstName.length > 100 || lastName.length > 100) {
            alert("First and last names must be under 100 characters.");
            return false;
        }

        return true;
    }
</script>

</body>
</html>
