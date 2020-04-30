<?php
require 'databasescript.php';
$DB = new objDatabaseConnection();

$connection = $DB->openConnection();

$availableTests = $DB->readData('SELECT name FROM test WHERE delete_date IS NULL');
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
</head>
<body>


<div class="container">
    <div class="columns">
        <?php include 'navigation.html' ?>
        <div class="column">
            <h1>Please choose a test</h1>
            <ul>
                <?php
                foreach ($availableTests as $test) {
                    echo "<li><a class='btn' href='test.php?test={$test['name']}'>{$test['name']}</a></li>";
                }

                ?>
            </ul>
        </div>
    </div>
</div>

</body>
</html>