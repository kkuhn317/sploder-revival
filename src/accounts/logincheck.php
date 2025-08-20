<?php

session_start();
if (($_SESSION['loggedin'] ?? false) == true) {
    header("Location: /");
    exit();
}
