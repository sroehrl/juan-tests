<?php

require 'databasescript.php';

if(isset($_GET['doInstall'])){
    $DB = new objDatabaseConnection();
    $sql = file_get_contents(__DIR__. '/database.sql');
    $connection = $DB->openConnection();
    $password = password_hash('123456', PASSWORD_DEFAULT);
    $sql .= "\n".'INSERT INTO user SET userName = "admin", is_admin ="1", password = "' . $password .'"';
    $connection->multi_query($sql);
    die('script ran');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Take a test</title>
    <!-- spectrecss: https://picturepan2.github.io/spectre -->
    <link rel="stylesheet" href="https://unpkg.com/spectre.css/dist/spectre.min.css">
    <link rel="stylesheet" href="https://unpkg.com/spectre.css/dist/spectre-exp.min.css">
    <link rel="stylesheet" href="https://unpkg.com/spectre.css/dist/spectre-icons.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Installation</h1>
    <p>Please delete this file after installation</p>
    <h3>1. Update your credentials in the file ".env"</h3>
    <p>Create a database first</p>
    <textarea class="form-input" >
        create database if not exists readcomptest;
    </textarea>
    <h3>2. Click this button</h3>
    <a href="?doInstall" class="btn">Install</a>
    <h3>The script tries to generate an administrator account</h3>
    <p>user: admin <br>password: 123456</p>

</div>
</body>
