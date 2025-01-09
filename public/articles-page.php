<?php
require_once dirname(__DIR__) . '../config/database.php';
require_once '../src/Model/Crud.php';
require_once '../src/Model/Article.php';
require_once '../src/Model/Category.php';
require_once '../src/Model/Tag.php';
require_once '../src/Model/User.php';

if (!isset($_GET['id'])) {
    // header('Location: index.php');
    // exit();
}

$db = DatabaseConnection::getInstance();
$pdo = $db->getPdo();
User::checkAuth();

$articleObj = new Article($pdo);
$categoryObj = new Category($pdo);
$tagObj = new Tag($pdo);


$article = $articleObj->getArticleById($_GET['id']);

if (!$article) {
    // header('Location: index.php');
    // exit();
}


$categories = $categoryObj->displayCategories();
$tags = $tagObj->displayTags();

if (isset($_GET['id'])) {
    $article = $articleObj->getArticleById($_GET['id']);
    
    // Increment the views when the article is viewed
    $articleObj->incrementViews($_GET['id']);
}

// if (session_status() === PHP_SESSION_NONE) {
//     session_start();
// }
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>DevBlog - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../src/css/sb-admin-2.min.css" rel="stylesheet">
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->


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

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                    </div>

                    <!-- -------------the form starts here:------------- -->
                    <div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Article Header -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <h1 class="card-title mb-3"><?= htmlspecialchars($article['title']) ?></h1>
                    
                    <!-- Article Metadata -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="metadata">
                            <span class="text-muted">
                                <i class="fas fa-user me-1"></i> <?= htmlspecialchars($article['username']) ?>
                            </span>
                            <span class="text-muted ms-3">
                                <i class="fas fa-calendar me-1"></i> <?= date('F j, Y', strtotime($article['created_at'])) ?>
                            </span>
                            <span class="text-muted ms-3">
                                <i class="fas fa-eye me-1"></i> <?= number_format($article['views']) ?> views
                            </span>
                        </div>
                        <div class="category">Category: 
                            <span class="badge badge-secondary mr-1">
                                <?= htmlspecialchars($article['category_name']) ?>
                            </span>
                        </div>
                    </div>

                    <!-- Tags -->
                    <?php if (!empty($article['tag_name'])): ?>
                    <div class="mb-4">
                        Tags:
                        <?php foreach (explode(',', $article['tag_name']) as $tag): ?>
                            <span class="badge badge-primary mr-1">
                                <?= htmlspecialchars(trim($tag)) ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <!-- Featured Image if exists
                    <?php if (!empty($article['featured_image'])): ?>
                    <div class="mb-4">
                        <img src="<?= htmlspecialchars($article['featured_image']) ?>" 
                             class="img-fluid rounded" 
                             alt="<?= htmlspecialchars($article['title']) ?>">
                    </div> -->
                    <?php endif; ?>

                    <!-- Article Content -->
                    <div class="article-content">
                        <?= nl2br(htmlspecialchars($article['content'])) ?>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <div class="d-flex justify-content-between mb-5">
                <a href="author-page.php" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Articles
                </a>
                <?php if (isset($_SESSION['user_id']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'author')): ?>
                <div>
                    <a href="add-article.php" class="btn btn-primary me-2">
                        <i class="fas fa-pen me-1"></i>Create
                    </a>
                    <a href="edit-article.php?article_id=<?= $article['id'] ?>" class="btn btn-primary me-2">
                        <i class="fas fa-edit me-1"></i>Edit
                    </a>
  

                    <a href="delete-article.php?article_id=<?= $article['id'] ?>"  class="btn btn-danger"
                       onclick="return confirm('Are you sure you want to delete this article?')">
                        <i class="fas fa-trash me-1"></i>Delete
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <?php include 'components/footer.php'; ?>

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

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

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>
        <!-- Initialize the pie chart -->
   <script>
    // Set new default font family and font color to mimic Bootstrap's default styling
    Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#858796';

    // Pie Chart
    var ctx = document.getElementById("categoryPieChart");
    var categoryPieChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($categories) ?>,
            datasets: [{
                data: <?= json_encode($counts) ?>,
                backgroundColor: <?= json_encode(array_slice($colors, 0, count($categories))) ?>,
                hoverBackgroundColor: <?= json_encode(array_slice($colors, 0, count($categories))) ?>,
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
                callbacks: {
                    label: function(tooltipItem, data) {
                        var dataset = data.datasets[tooltipItem.datasetIndex];
                        var total = dataset.data.reduce(function(previousValue, currentValue) {
                            return previousValue + currentValue;
                        });
                        var currentValue = dataset.data[tooltipItem.index];
                        var percentage = Math.floor(((currentValue/total) * 100)+0.5);
                        return data.labels[tooltipItem.index] + ': ' + currentValue + ' (' + percentage + '%)';
                    }
                }
            },
            legend: {
                display: false
            },
            cutoutPercentage: 80,
        },
    });
   </script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../src/js/demo/datatables-demo.js"></script>
</body>