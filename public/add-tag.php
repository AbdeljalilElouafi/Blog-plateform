<?php

require_once dirname(__DIR__) . '../config/database.php';
require_once '../src/Model/Tag.php';

$db = new DatabaseConnection();
$pdo = $db->getPdo();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $name = $_POST['title'];

    // Assuming $pdo is your database connection
    $tag = new Tag($pdo);

    // Call the createTag method to add the new tag to the database
    $tag->addTag($name);
}


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Tag</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">

    <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-lg">
        <h1 class="text-2xl font-semibold text-gray-800 mb-6">Add New Tag</h1>

        <form action="add-tag.php" method="POST" enctype="multipart/form-data">
            <!-- Title -->
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">Tag name</label>
                <input type="text" id="title" name="title" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
            </div>

            

            <!-- Author ID (hidden, should be set server-side) -->
            <input type="hidden" name="author_id" value="1"> <!-- Example author ID -->

            <div class="flex items-center justify-between mt-6">
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">Add Tag</button>
            </div>
        </form>
    </div>


</body>
</html>
