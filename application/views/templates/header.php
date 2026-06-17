<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'TTPG' ?></title>
    <link rel="shortcut icon" href="<?= base_url('assets/images/logo_pulo_gebang.jpg') ?>" type="image/jpeg">

    <!-- AdminLTE -->
    <link rel="stylesheet" href="<?= base_url('assets/plugins/adminlte/css/adminlte.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/plugins/adminlte/plugins/fontawesome-free/css/all.min.css') ?>">

    <!-- Custom -->
    <link rel="stylesheet" href="<?= base_url('assets/css/custom.css') ?>">

    <!-- jQuery (Loaded early for inline scripts) -->
    <script src="<?= base_url('assets/plugins/adminlte/plugins/jquery/jquery.min.js') ?>"></script>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

<!-- NAVBAR -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="<?= site_url('dashboard') ?>" class="nav-link">Dashboard</a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <img src="<?= base_url('assets/images/avatar.png') ?>" class="img-circle elevation-2" width="30">
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a href="<?= site_url('auth/logout') ?>" class="dropdown-item">Logout</a>
            </div>
        </li>
    </ul>
</nav>