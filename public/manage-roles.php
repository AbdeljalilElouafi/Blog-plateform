<?php
require_once dirname(__DIR__) . '../config/database.php';
require_once '../src/Model/User.php';

$db = DatabaseConnection::getInstance();
$pdo = $db->getPdo();
$userObj = new User($pdo);
User::checkAuth();

// Fetch all users
$users = $userObj->getUsers();  // Assuming getUsers fetches all users (you can modify the method)

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_role'])) {
    // Update the role if form is submitted
    $userId = $_POST['user_id'];
    $newRole = $_POST['role'];

    $result = $userObj->updateRole($userId, $newRole);
    // if ($result) {
    //     $message = "Role updated successfully!";
    // } else {
    //     $message = "Failed to update role. Please try again.";
    // }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>DevBlog - Users Management</title>
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
                        <h1 class="h3 mb-0 text-gray-800">User Management</h1>
                    </div>

                    

                    <div class="container">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Users List</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Username</th>
                                                <th>Email</th>
                                                <th>Role</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($users as $user): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($user['username']) ?></td>
                                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                                    <td>
                                                        <span class="badge badge-<?= $user['role'] === 'admin' ? 'danger' : 'primary' ?>">
                                                            <?= ucfirst(htmlspecialchars($user['role'])) ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <form method="POST" action="manage-roles.php">
                                                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                                            <select name="role" class="form-control form-control-sm" required>
                                                                <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>User</option>
                                                                <option value="author" <?= $user['role'] == 'author' ? 'selected' : '' ?>>Author</option>
                                                                <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                                                            </select>
                                                            <button type="submit" name="update_role" class="btn btn-warning btn-sm mt-2">
                                                                Update Role
                                                            </button>
                                                        </form>
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
