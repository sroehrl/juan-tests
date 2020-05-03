<?php
session_start();
$_SESSION['logged_in'] = false;
$_SESSION['user'] = [];
header('Location: index.php');
