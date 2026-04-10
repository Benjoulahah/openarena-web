<?php

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

switch ($page) {
    case 'admin':
        require_once __DIR__ . '/../app/admin.php';
        break;

    case 'home':
    default:
        require_once __DIR__ . '/../app/home.php';
        break;
}