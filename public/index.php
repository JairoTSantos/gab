<?php

session_start();

if (!isset($_SESSION['usuario_token'])) {
    header("Location: login.php");
    exit();
} else {
    header("Location: home.php");
    exit();
}
