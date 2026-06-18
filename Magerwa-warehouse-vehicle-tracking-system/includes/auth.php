<?php
// includes/auth.php

session_start();

function isLoggedIn() {
    return isset($_SESSION['admin_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ../pages/login.php');
        exit();
    }
}

function login($adminId, $email, $names) {
    $_SESSION['admin_id'] = $adminId;
    $_SESSION['admin_email'] = $email;
    $_SESSION['admin_names'] = $names;
}

function logout() {
    session_destroy();
    header('Location: ../pages/login.php');
    exit();
}
?>