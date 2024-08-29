<?php

session_start();

if (!isset($_SESSION['usuario_token'])) {
    header("Location: ./login");
    exit();
} else {
    header("Location: ./home");
    exit();
}
