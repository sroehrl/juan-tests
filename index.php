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
</head>
<body>
<h1>Please choose a test</h1>
<ul>
<?php
foreach ($availableTests as $test){
    echo "<li><a class='btn' href='test.php?test={$test['name']}'>{$test['name']}</a></li>";
}

?>
</ul>
</body>
</html>