<?php

require_once dirname(__DIR__) . '../config/database.php';
require_once '../src/Model/Category.php';

$db = DatabaseConnection::getInstance();
$pdo = $db->getPdo();


if (isset($_GET['Category_id'])) {
    $Category_id = $_GET['Category_id'];

    
    $Category = new Category($pdo);

    
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = :id");
    $stmt->bindParam(':id', $Category_id, PDO::PARAM_INT);
    $stmt->execute();
    $Category_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$Category_data) {
        echo "Category not found.";
        exit;
    }

    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        if (isset($_POST['Category_id']) && isset($_POST['name'])) {
            $Category_id = $_POST['Category_id'];  
            $new_name = $_POST['name'];  

            
            $Category->editCategory($Category_id, $new_name);

            echo "Category updated successfully!";
        } else {
            echo "Category ID and Name are required.";
        }
    }
} else {
    echo "Category ID is required.";
    exit;
}

?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">

    <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-lg">
        <h1 class="text-2xl font-semibold text-gray-800 mb-6">Add New Category</h1>

        <form action="edit-category.php?Category_id=<?php echo $Category_data['id']; ?>" method="POST" enctype="multipart/form-data">
            <!-- Title -->
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">Category</label>
                <input type="text" id="title" name="name" value="<?php echo $Category_data['name']; ?>" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
            </div>

            <input type="hidden" name="Category_id" value="<?php echo $Category_data['id']; ?>">

            <div class="flex items-center justify-between mt-6">
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">Add Category</button>
            </div>
        </form>
    </div>

    

</body>
</html>
