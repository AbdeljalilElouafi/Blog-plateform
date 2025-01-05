<?php
require_once dirname(__DIR__) . '../config/database.php';
require_once '../src/Model/Article.php';

$db = new DatabaseConnection();
$pdo = $db->getPdo();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $articleData = [
        'title' => $_POST['title'],
        'content' => $_POST['content'],
        'category_id' => $_POST['category_id'],
        'status' => 'draft'
        
    ];

    // print_r($articleData);

    try {
        $article = new Article($pdo);
        $article->addArticle($articleData);
        

        $articleId = $pdo->lastInsertId();
        
        // to add multiple tags in one article
        if (isset($_POST['tag_id']) && is_array($_POST['tag_id'])) {
            foreach ($_POST['tag_id'] as $tagId) {
                $article->addTag($articleId, $tagId);
            }
        }
        
        // header('Location: index.php?success=1');
        // exit;
    } catch (Exception $e) {
        $error = "Error adding article: " . $e->getMessage();
    }
}
    // print_r($articleData);


try {
    // echo "in the categories condition";

    $stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $categories = [];
    $error = "Error fetching categories: " . $e->getMessage();
}


try {

    $stmt = $pdo->query("SELECT * FROM tags ORDER BY name");
    $tags = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $tags = [];
    $error = "Error fetching tags: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Article</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-8">
    <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-lg">
        <h1 class="text-2xl font-semibold text-gray-800 mb-6">Add New Article</h1>

        <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="add-article.php" method="POST" enctype="multipart/form-data">
            <!-- Title -->
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" id="title" name="title" 
                    value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                    required>
            </div>

            <!-- Content -->
            <div class="mb-4">
                <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                <textarea id="content" name="content" rows="6" 
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                    required><?php echo isset($_POST['content']) ? htmlspecialchars($_POST['content']) : ''; ?></textarea>
            </div>

            <!-- Category -->
            <div class="mb-4">
                <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                <select id="category_id" name="category_id" 
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                    required>
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>" 
                            <?php echo (isset($_POST['category_id']) && $_POST['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

                        <!-- Tags -->
                        <div class="mb-4">
                                <label for="tags" class="block text-sm font-medium text-gray-700">Tags</label>
                                <div id="tags" class="space-y-2">
                                    <?php foreach ($tags as $tag): ?>
                                        <div class="flex items-center">
                                            <input type="checkbox" id="tag_<?php echo $tag['id']; ?>" name="tag_id[]" value="<?php echo $tag['id']; ?>"
                                                <?php echo (isset($_POST['tag_id']) && in_array($tag['id'], $_POST['tag_id'])) ? 'checked' : ''; ?>>
                                            <label for="tag_<?php echo $tag['id']; ?>" class="ml-2"><?php echo htmlspecialchars($tag['name']); ?></label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>





            <div class="flex items-center justify-between mt-6">
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">Add Article</button>
                <a href="index.php" class="px-6 py-2 bg-gray-500 text-white font-semibold rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>