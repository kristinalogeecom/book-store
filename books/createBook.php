<!DOCTYPE html>
<html>
<head>
    <title>Create Book</title>
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

        .icon-blue {
            color: #4a90e2;
        }


    </style>
</head>
<body>

<div class="form-container">
    <h2><i class="fa-solid fa-book icon-blue"></i> Create New Book</h2>

    <form action="saveBook.php" method="POST" onsubmit="return validateForm()">
        <label for="title">Book Title:</label>
        <input type="text" id="title" name="title" maxlength="250" required>

        <label for="year">Year:</label>
        <input type="number" id="year" name="year" required min="-5000" max="999999">

        <input type="submit" value="Create Book">
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
