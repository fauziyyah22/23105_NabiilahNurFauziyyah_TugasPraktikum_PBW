<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['name'])) {
    header("location:auth/login.php");
    exit(); // Pastikan script berhenti di sini setelah redirect
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Articles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>

    <!-- Validation below for user to ensure that they are already logged -->

    <div class="container my-5">
        <div class="row">

            <!-- See the query params (messages) in the url? Can you get that and show it in page? -->
            <!-- Tambahkan informasi message error/success disini -->

            <?php
            if (isset($_GET['message'])) {
                $message = $_GET['message'];
            ?>
                <div class="alert alert-success"><?= $message ?></div>
            <?php
            }
            ?>

            <div class="col-md-12">
                <div class="card border-0">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <h2>List Articles</h2>
                            <div>
                                <a href="add.php" class="btn btn-primary">Add Article</a>
                                <a href="auth/logout.php" class="btn btn-danger">Logout</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Content</th>
                                    <th>Category</th>
                                    <th>Thumbnail</th>
                                    <th>Act</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                include "koneksi.php";
                                $no = 1;
                                $query = mysqli_query($koneksi, "SELECT * FROM articles");
                                foreach ($query as $data) {
                                ?>
                                    <tr>
                                        <t><?= $no++ ?></td>
                                            <td><?= $data['title'] ?></td>
                                            <td><?= $data['content'] ?></td>
                                            <td><?= $data['category'] ?></td>
                                            <td>
                                                <img src="images/<?= $data['thumbnail'] ?>" class="img-fluid img-thumbnail">
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="edit.php?id=<?= $data['id'] ?>" class="btn btn-warning mx-2">Edit</a>
                                                    <a href="delete.php?id=<?= $data['id'] ?>" class="btn btn-danger mx-2">Delete</a>
                                                </div>
                                            </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>