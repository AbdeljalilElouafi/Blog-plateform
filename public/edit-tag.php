<?php

require_once dirname(__DIR__) . '../config/database.php';
require_once '../src/Model/Tag.php';
require_once '../src/Model/User.php';


$db = DatabaseConnection::getInstance();
$pdo = $db->getPdo();
User::checkAuth();

if (isset($_GET['tag_id'])) {
    $tag_id = $_GET['tag_id'];

    
    $tag = new Tag($pdo);

    
    $stmt = $pdo->prepare("SELECT * FROM tags WHERE id = :id");
    $stmt->bindParam(':id', $tag_id, PDO::PARAM_INT);
    $stmt->execute();
    $tag_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$tag_data) {
        echo "Tag not found.";
        exit;
    }

    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        if (isset($_POST['tag_id']) && isset($_POST['name'])) {
            $tag_id = $_POST['tag_id'];  
            $new_name = $_POST['name'];  

            
            $tag->editTag($tag_id, $new_name);

            echo "Tag updated successfully!";
        } else {
            echo "Tag ID and Name are required.";
        }
    }
} else {
    echo "Tag ID is required.";
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tag</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">

    <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-lg">
        <h1 class="text-2xl font-semibold text-gray-800 mb-6">Edit Tag</h1>

        
        <form action="edit-tag.php?tag_id=<?php echo $tag_data['id']; ?>" method="POST" enctype="multipart/form-data">
            
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">Tag name</label>
                <input type="text" id="title" name="name" value="<?php echo $tag_data['name']; ?>" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
            </div>

            
            <input type="hidden" name="tag_id" value="<?php echo $tag_data['id']; ?>">

            <div class="flex items-center justify-between mt-6">
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">Update Tag</button>
            </div>
        </form>
    </div>

</body>
</html>
