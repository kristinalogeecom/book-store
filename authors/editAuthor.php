<?php
session_start();
if (!isset($_GET['id'])) {
    header('Location: listOfAuthors.php');
    exit;
}

$authorId = (int) $_GET['id'];

if (!isset($_SESSION['authors'])) {
    $_SESSION['authors'] = [];
}

$authors = &$_SESSION['authors'];
$author = null;
foreach ($authors as &$a) {
    if ($a['id'] == $authorId) {
        $author = &$a;
        break;
    }
}

if (!$author) {
    header('Location: listOfAuthors.php');
    exit;
}

$errorMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['first_name']);
    $lastName = trim($_POST['last_name']);

    if (empty($firstName) || empty($lastName)) {
        $errorMessage = '* Both fields are required';
    } elseif (strlen($firstName) > 100 || strlen($lastName) > 100) {
        $errorMessage = '* First name and last name cannot be longer than 100 characters.';
    } else {
        $author['first_name'] = $firstName;
        $author['last_name'] = $lastName;

        header('Location: listOfAuthors.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Author</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .form-container {
            width: 280px;
            margin: 40px auto;
            padding: 20px;
            border: 1px solid #ccc;
            background-color: #fff;
        }

        h2 {
            margin-bottom: 10px;
            font-size: 14px;
            color: #666666;
            font-weight: normal;
        }

        label {
            display: block;
            margin-top: 15px;
            font-size: 14px;
            color: #666666;
        }

        input[type="text"] {
            width: 100%;
            padding: 6px;
            border: 1px solid;
            box-sizing: border-box;
        }

        .required-msg {
            color: red;
            font-size: 13px;
            margin-top: 4px;
        }

        .btn-wrapper {
            display: flex;
            justify-content: flex-end;
            margin-top: 10px;
        }

        button {
            padding: 8px 20px;
            font-size: 16px;
            font-weight: bold;
            background-color: #64b5f6;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            width: auto;
        }

        button:hover {
            background-color: #42a5f5;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2> Edit Author <span style="font-size: 14px;">(<?= $author['id'] ?>)</span></h2>
    <hr style="border: 1px solid #BEDCFE;">
    <form method="post" action="">
        <label for="first_name">First Name</label>
        <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($author['first_name']) ?>">
        <?php if ($_SERVER['REQUEST_METHOD'] === "POST" && (empty($_POST['first_name']) || strlen($_POST['first_name']) > 100)): ?>
            <div class="required-msg"><?= $errorMessage ?></div>
        <?php endif; ?>

        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($author['last_name']) ?>">
        <?php if ($_SERVER['REQUEST_METHOD'] === "POST" && (empty($_POST['last_name']) || strlen($_POST['last_name']) > 100)): ?>
            <div class="required-msg"><?= $errorMessage ?></div>
        <?php endif; ?>
        <div class="btn-wrapper">
            <button type="submit">Save</button>
        </div>
    </form>
</div>


</body>
</html>
