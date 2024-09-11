<?php

session_start();

if (!isset($_SESSION['usuario_token'])) {
    header("Location: public/login.php");
    exit();
} else {
    header("Location: public/home.php");
    exit();
}
