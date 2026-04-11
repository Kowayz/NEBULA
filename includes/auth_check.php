<?php
session_start();

if (empty($_SESSION['user_id'])) {
    header('Location: /NEBULA/auth.php');
    exit;
}
