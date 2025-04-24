<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $firstName = trim($_POST['first_name']);
    $lastName = trim($_POST['last_name']);

    if (strlen($firstName) > 100 || strlen($lastName) > 100) {
        $error = "* First name and last name cannot be longer than 100 characters.";
    } else {
        $maxId = 0;
        if (isset($_SESSION['authors'])) {
            foreach ($_SESSION['authors'] as $a) {
                if ($a['id'] > $maxId) {
                    $maxId = $a['id'];
                }
            }
        }

        if (!empty($firstName) && !empty($lastName)) {

            $newAuthor = [
                'id' => $maxId + 1,
                'first_name' => $firstName,
                'last_name' => $lastName
            ];

            if (!isset($_SESSION['authors'])) {
                $_SESSION['authors'] = [];
            }

            $_SESSION['authors'][] = $newAuthor;
            header('Location: listOfAuthors.php');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Author Create</title>
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

        .error-msg {
            color: red;
            font-size: 12px;
            margin-top: 4px;
            margin-bottom: 10px;
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
    <h2>Author Create</h2>
    <hr style="border: 1px solid #BEDCFE;">
    <form method="post" action="">
        <label for="first_name">First name</label>
        <input type="text" name="first_name" id="first_name"  value="<?= htmlspecialchars(isset($_POST['first_name']) ? $_POST['first_name'] : '') ?>">
        <?php if ($_SERVER['REQUEST_METHOD'] === "POST" && empty($_POST['first_name'])): ?>
            <div class="required-msg">* This field is required</div>
        <?php endif; ?>

        <label for="last_name">Last name</label>
        <input type="text" name="last_name" id="last_name"  value="<?= htmlspecialchars(isset($_POST['last_name']) ? $_POST['last_name'] : '') ?>">
        <?php if ($_SERVER['REQUEST_METHOD'] === "POST" && empty($_POST['last_name'])): ?>
            <div class="required-msg">* This field is required</div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="error-msg"><?= $error ?></div>
        <?php endif; ?>

        <div class="btn-wrapper">
            <button type="submit">Save</button>
        </div>
    </form>
</div>

</body>
</html>
