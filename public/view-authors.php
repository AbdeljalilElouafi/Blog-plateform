<?php
require_once dirname(__DIR__) . '../config/database.php';
require_once '../src/Model/User.php';

$db = DatabaseConnection::getInstance();
$pdo = $db->getPdo();
$userObj = new User($pdo);
$authors = $userObj->getAuthors();
// User::checkAuth();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>DevBlog - Authors Management</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
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
                        <h1 class="h3 mb-0 text-gray-800">Authors Management</h1>
                    </div>

                    <div class="container">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Authors List</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Username</th>
                                                <th>Email</th>
                                                <th>Role</th>
                                                <th>Articles Count</th>
                                                <th>Bio</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($authors as $author): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($author['username']) ?></td>
                                                    <td><?= htmlspecialchars($author['email']) ?></td>
                                                    <td>
                                                        <span class="badge badge-<?= $author['role'] === 'admin' ? 'danger' : 'primary' ?>">
                                                            <?= ucfirst(htmlspecialchars($author['role'])) ?>
                                                        </span>
                                                    </td>
                                                    <td><?= $author['article_count'] ?></td>
                                                    <td>
                                                        <?= $author['bio'] ? htmlspecialchars(substr($author['bio'], 0, 50)) . '...' : 'No bio' ?>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <a href="profile.php?id=<?= $author['id'] ?>" 
                                                               class="btn btn-info btn-sm">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <a href="edit-author.php?id=<?= $author['id'] ?>" 
                                                               class="btn btn-primary btn-sm">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <?php if ($_SESSION['role'] === 'admin'): ?>
                                                                <a href="delete-author.php?id=<?= $author['id'] ?>" 
                                                                   class="btn btn-danger btn-sm"
                                                                   onclick="return confirm('Are you sure you want to delete this author?');">
                                                                    <i class="fas fa-trash"></i>
                                                                </a>
                                                            <?php endif; ?>
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