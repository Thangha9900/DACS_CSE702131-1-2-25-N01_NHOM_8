<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/../../utils/utility.php';
require_once __DIR__ . '/../../database/dbhelpere.php';

$adminUrl = '/webbanhang/admin/';

$user = getUserToken();
if ($user == null) {
    header('Location: ' . $adminUrl . 'authen/login.php');
    die();
} else if ($user['role_id'] != 1) {
    header('Location: /webbanhang/index.php');
    die();
}
?>


<!DOCTYPE html>
<html>

<head>
    <title><?= $title ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="shortcut icon" href="../../assets/photos/logo.jpg">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="/webbanhang/assets/css/dashboard.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.6.1/font/bootstrap-icons.css">

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</head>

<body>

    <!-- ===== NAVBAR ===== -->
    <nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="<?= $adminUrl ?>">ADMIN </a>
        <ul class="navbar-nav px-3">
            <li class="nav-item text-nowrap">
                <a class="nav-link" href="<?= $adminUrl ?>authen/logout.php">Thoát</a>
            </li>
        </ul>
    </nav>

    <!-- ===== LAYOUT ===== -->
    <div class="container-fluid">
        <div class="row">

            <!-- ===== SIDEBAR ===== -->
            <nav class="col-md-2 d-none d-md-block bg-light sidebar">
                <div class="sidebar-sticky pt-3">
                    <ul class="nav flex-column">

                        <li class="nav-item">
                            <a class="nav-link active" href="<?= $adminUrl ?>">
                                <i class="bi bi-house-fill"></i>
                                THỐNG KÊ
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="<?= $adminUrl ?>category">
                                <i class="bi bi-folder"></i>
                                Danh Mục Sản Phẩm
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="<?= $adminUrl ?>product">
                                <i class="bi bi-file-earmark-text"></i>
                                Sản Phẩm
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="<?= $adminUrl ?>order">
                                <i class="bi bi-minecart"></i>
                                Quản Lý Đơn Hàng
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="<?= $adminUrl ?>feedback">
                                <i class="bi bi-question-circle-fill"></i>
                                Quản Lý Phản Hồi
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="<?= $adminUrl ?>user">
                                <i class="bi bi-people-fill"></i>
                                Quản Lý Người Dùng
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- ===== MAIN CONTENT ===== -->
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4 mt-4">
                <!-- hien thi tung chuc nang cua trang quan tri START-->
