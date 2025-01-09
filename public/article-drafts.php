<?php
require_once dirname(__DIR__) . '../config/database.php';
require_once '../src/Model/Article.php';
require_once '../src/Model/User.php';

$db = DatabaseConnection::getInstance();
$pdo = $db->getPdo();
$articleObj = new Article($pdo);
User::checkAuth();


if ($_SESSION['role'] !== 'admin') {
    header('Location: unauthorized.php');
    exit();
}

$articles = $articleObj->getArticlesByStatus('draft');

if (isset($_GET['action']) && isset($_GET['id'])) {
    $articleId = $_GET['id'];
    $action = $_GET['action'];

    if ($action === 'publish') {
        $articleObj->changeStatus($articleId, 'published');
    }
    header("Location: article-drafts.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>DevBlog - Manage Articles</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../src/css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">
    <div id="wrapper">
        <?php include 'components/sidebar.php'; ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include 'components/topbar.php'; ?>
                
                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Article Drafts Management</h1>
                    </div>

                    <div class="container">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Pending Articles</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th>Category</th>
                                                <th>Author</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($articles as $article): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($article['title']) ?></td>
                                                    <td><?= htmlspecialchars($article['category_name']) ?></td>
                                                    <td><?= htmlspecialchars($article['username']) ?></td>
                                                    <td>
                                                        <span class="badge badge-warning"><?= ucfirst(htmlspecialchars($article['status'])) ?></span>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <a href="edit-article.php?id=<?= $article['id'] ?>" 
                                                               class="btn btn-primary btn-sm">
                                                                <i class="fas fa-edit"></i> Edit
                                                            </a>
                                                            <a href="article-drafts.php?action=publish&id=<?= $article['id'] ?>" 
                                                               class="btn btn-success btn-sm"
                                                               onclick="return confirm('Are you sure you want to publish this article?');">
                                                                <i class="fas fa-check"></i> Publish
                                                            </a>
                                                            <a href="article-drafts.php?action=schedule&id=<?= $article['id'] ?>" 
                                                               class="btn btn-info btn-sm"
                                                               onclick="return confirm('Are you sure you want to schedule this article?');">
                                                                <i class="fas fa-clock"></i> Schedule
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include 'components/footer.php'; ?>
        </div>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="../src/js/demo/datatables-demo.js"></script>
</body>
</html>
