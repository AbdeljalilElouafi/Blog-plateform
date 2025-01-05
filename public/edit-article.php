<?php
require_once dirname(__DIR__) . '../config/database.php';
require_once '../src/Model/Article.php';

$db = new DatabaseConnection();
$pdo = $db->getPdo();
$article = new Article($pdo);

// to get the article id from the url
$article_id = isset($_GET['article_id']) ? (int)$_GET['article_id'] : 0;

// to get the article data to the form
try {
    $stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
    $stmt->execute([$article_id]);
    $articleData = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$articleData) {
        header('Location: index.php?error=Article not found');
        exit;
    }
} catch(PDOException $e) {
    header('Location: index.php?error=' . urlencode($e->getMessage()));
    exit;
}


try {
    $stmt = $pdo->prepare("SELECT tag_id FROM article_tags WHERE article_id = ?");
    $stmt->execute([$article_id]);
    $currentTags = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch(PDOException $e) {
    $currentTags = [];
    $error = "Error fetching article tags: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updateData = [
        'title' => $_POST['title'],
        'content' => $_POST['content'],
        'category_id' => $_POST['category_id'],
        'updated_at' => date('Y-m-d H:i:s')
    ];

    try {
        
        $article->editArticle($article_id, $updateData);
    
        
        $stmt = $pdo->prepare("DELETE FROM article_tags WHERE article_id = ?");
        $stmt->execute([$article_id]);
    
        
        if (isset($_POST['tag_id']) && is_array($_POST['tag_id'])) {
            foreach ($_POST['tag_id'] as $tagId) {
                $article->addTag($article_id, $tagId);
            }
        }
    
        
        // header('Location: index.php?success=Article updated successfully');
        // exit;
    
    } catch (Exception $e) {
        
        $error = "Error updating article: " . $e->getMessage();
    }
    
}

try {
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
    <title>Edit Article</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-lg">
        <h1 class="text-2xl font-semibold text-gray-800 mb-6">Edit Article</h1>

        <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="edit-article.php?article_id=<?php echo $article_id; ?>" method="POST" enctype="multipart/form-data">
            <!-- Title -->
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" id="title" name="title" 
                    value="<?php echo htmlspecialchars($articleData['title']); ?>"
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                    required>
            </div>

            <!-- Content -->
            <div class="mb-4">
                <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                <textarea id="content" name="content" rows="6" 
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                    required><?php echo htmlspecialchars($articleData['content']); ?></textarea>
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
                            <?php echo ($articleData['category_id'] == $category['id']) ? 'selected' : ''; ?>>
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
                                    <input type="checkbox" 
                                        id="tag_<?php echo $tag['id']; ?>" 
                                        name="tag_id[]" 
                                        value="<?php echo $tag['id']; ?>"
                                        <?php echo (in_array($tag['id'], $currentTags)) ? 'checked' : ''; ?>>
                                    <label for="tag_<?php echo $tag['id']; ?>" class="ml-2">
                                        <?php echo htmlspecialchars($tag['name']); ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>


            <!-- Status -->
            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select id="status" name="status" 
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="draft" <?php echo ($articleData['status'] == 'draft') ? 'selected' : ''; ?>>Draft</option>
                    <option value="published" <?php echo ($articleData['status'] == 'published') ? 'selected' : ''; ?>>Published</option>
                    <option value="scheduled" <?php echo ($articleData['status'] == 'scheduled') ? 'selected' : ''; ?>>Scheduled</option>
                </select>
            </div>

            <div class="flex items-center justify-between mt-6">
                <button type="submit" 
                    class="px-6 py-2 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
                    Update Article
                </button>
                <a href="index.php" 
                    class="px-6 py-2 bg-gray-500 text-white font-semibold rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50">
                    Cancel
                </a>
            </div>
        </form>
    </div>

</body>
</html>