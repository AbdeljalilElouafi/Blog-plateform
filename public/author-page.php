<?php
require_once dirname(__DIR__) . '../config/database.php';
require_once '../src/Model/Crud.php';
require_once '../src/Model/Article.php';
require_once '../src/Model/Category.php';
require_once '../src/Model/Tag.php';
require_once '../src/Model/User.php';

$db = DatabaseConnection::getInstance();
$pdo = $db->getPdo();

User::checkAuth();
$articleObj = new Article($pdo);
$categoryObj = new Category($pdo);
$tagObj = new Tag($pdo);
$articles = $articleObj->displayArticles();


// Get the article details by ID
// if (isset($_GET['id'])) {
//     $articles = $articleObj->getArticleById($_GET['id']);
    
//     // Increment the views when the article is viewed
//     $articleObj->incrementViews($_GET['id']);
// }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>DevBlog - Article</title>

    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../src/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700" rel="stylesheet">

    <style>
        /* Add the hover effect CSS from above here */
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            padding: 20px;
        }

        .card-title a {
            font-size: 18px;
            color: #007bff;
            text-decoration: none;
        }

        .card-title a:hover {
            text-decoration: underline;
        }

        .card .category {
            margin-top: 10px;
        }

        .card .tags {
            margin-top: 10px;
            display: flex;
            flex-wrap: wrap;
        }

        .card .tags .badge {
            margin-right: 5px;
        }
    </style>
</head>
<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php include 'components/sidebar.php'; ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php include 'components/topbar.php'; ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">All Articles</h1>
                    </div>

                    <div class="container">
                        <div class="row justify-content-center">
                            <?php foreach ($articles as $article): ?>
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card shadow">
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <a href="articles-page.php?id=<?= $article['id'] ?>" class="btn btn-link"><?= htmlspecialchars($article['title']) ?></a>
                                        </h5>

                                        <p class="text-muted">
                                            <i class="fas fa-user me-1"></i> <?= htmlspecialchars($article['username']) ?>
                                            <span class="ms-3">
                                                <i class="fas fa-calendar me-1"></i> <?= date('F j, Y', strtotime($article['created_at'])) ?>
                                            </span>
                                        </p>

                                        <p class="text-muted">
                                            <i class="fas fa-eye me-1"></i> <?= number_format($article['views']) ?> views
                                        </p>

                                        <div class="category">
                                            <span class="badge badge-secondary"><?= htmlspecialchars($article['category_name']) ?></span>
                                        </div>

                                        <?php if (!empty($article['tag_name'])): ?>
                                        <div class="tags mt-2">
                                            <?php foreach (explode(',', $article['tag_name']) as $tag): ?>
                                                <span class="badge badge-primary"><?= htmlspecialchars(trim($tag)) ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                </div> <!-- /.container-fluid -->
            </div> <!-- End of Main Content -->

            <?php include 'components/footer.php'; ?>

        </div> <!-- End of Content Wrapper -->

    </div> <!-- End of Page Wrapper -->

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="../src/pages/logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>

</body>
</html>

