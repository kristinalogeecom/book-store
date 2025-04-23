<!DOCTYPE html>
<html>
<head>
    <title>Create Author</title>
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
    <h2><i class="fa-solid fa-user" style="color: #4a90e2;"></i> Create New Author</h2>

    <form action="saveAuthor.php" method="POST" onsubmit="return validateForm()">
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" maxlength="100" required>

        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" maxlength="100" required>

        <input type="submit" value="Create Author">
    </form>

    <a class="back-link" href="listOfAuthors.php"><i class="fa-solid fa-arrow-left"></i> Back to Author List</a>
</div>

<script>
    function validateForm() {
        const firstName = document.getElementById('first_name').value.trim();
        const lastName = document.getElementById
