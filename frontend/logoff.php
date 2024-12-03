<?php
session_start();
session_unset();

if (session_status() == PHP_SESSION_ACTIVE) {
    session_destroy();
    header("Location: ./login.php");
}