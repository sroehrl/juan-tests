<?php

require 'databasescript.php';

if(isset($_GET['doInstall'])){
    $DB = new objDatabaseConnection();
    $sql = file_get_contents(__DIR__. '/database.sql');
    $connection = $DB->rawConnection();
    $password = password_hash($_GET['password'], PASSWORD_DEFAULT);
    $sql .= "\n".'INSERT INTO user SET userName = "admin", is_admin ="1", password = "' . $password .'"';
    $connection->multi_query($sql);
    $connection->close();
    echo "<a href='login.php'>Login</a>";
    die();
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
    <form>
        <input type="hidden" name="doInstall" value="true">
        <p>Please delete this file after installation</p>
        <h3>1. Update your credentials in the file ".env"</h3>
        <h3>2. The script tries to generate the database & create an administrator account</h3>
        <p>user: admin</p>
        <div class="form-group">
            <label for="pw">admin password (default: 123456)</label>
            <input class="form-input" type="password" name="password" id="pw" value="123456">
        </div>
        <button class="btn">Run installation</button>
    </form>



</div>
</body>
