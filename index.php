<?php
session_start();
if(!isset($_SESSION['logged_in'])){
    $_SESSION['logged_in'] = false;
    header('Location: login.php');
}
require 'databasescript.php';
$DB = new objDatabaseConnection();

$connection = $DB->openConnection();

$availableTests = [];
if($_SESSION['logged_in']){
    $availableTests = $DB->readData('SELECT t.name, a.* FROM test t JOIN assignment a on t.id = a.test_id AND a.user_id = '.$_SESSION['user']['id'].' WHERE t.delete_date IS NULL ORDER BY id DESC');
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
    <div class="columns">
        <?php require 'navigation.php' ?>
        <div class="column">
            <h1><?= $_SESSION['logged_in'] ? 'Hello, ' .$_SESSION['user']['userName'] .'! ' : '' ?></h1>
            <ul class="no-list-style">
                <?php
                $greeting = '';
                foreach ($availableTests as $test){
                    if(empty($test['completion_date'])){
                        $greeting = '<h2>You have open assignments</h2>';
                    }
                }

                echo $greeting;
                if($_SESSION['logged_in']){
                    foreach ($availableTests as $test) {
                        if($test['completion_date']){
                            echo "<li>Test <strong>\"{$test['name']}\"</strong> completed ".date( 'm/d/Y', strtotime($test['completion_date']))." with score {$test['score']}</li>";
                        } else {
                            echo "<li><a class='btn' href='test.php?test={$test['name']}'>Assignment: {$test['name']}</a></li>";
                        }

                    }
                } else {
                    echo "<li><a class='btn' href='login.php'>Please log in first</a></li>";
                }


                ?>
            </ul>
        </div>
    </div>
</div>

</body>
</html>